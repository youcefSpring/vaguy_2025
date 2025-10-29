<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Instagram Apify Service
 * PHP wrapper for Node.js Instagram scraper using Apify
 */
class InstagramApifyService
{
    private string $apiToken;
    private string $nodeScriptPath;
    private int $timeout;

    public function __construct(
        string $apiToken = null,
        int $timeout = 120
    ) {
        $this->apiToken = $apiToken ?: env('APIFY_TOKEN', '');
        $this->timeout = $timeout;
        $this->nodeScriptPath = base_path('instagram-scraper.js');
    }

    /**
     * Scrape Instagram profile data
     *
     * @param string $username Instagram username (without @)
     * @param array $options Additional options
     * @return array Scraped data and analysis
     */
    public function scrapeProfile(string $username, array $options = []): array
    {
        $startTime = microtime(true);

        try {
            Log::info("Starting Instagram scrape for: @{$username}");

            // Clean username
            $cleanUsername = str_replace('@', '', trim($username));

            // Validate username
            if (empty($cleanUsername) || !preg_match('/^[a-zA-Z0-9._]+$/', $cleanUsername)) {
                throw new \InvalidArgumentException("Invalid Instagram username: {$username}");
            }

            // Try Express.js scraper service first
            try {
                $expressScraperService = new \App\Services\ExpressScraperService();
                Log::info("Attempting to use Express.js scraper service for: @{$cleanUsername}");
                $result = $expressScraperService->scrapeProfile($cleanUsername, $options);

                if ($result['success'] && !($result['metadata']['is_mock'] ?? false)) {
                    Log::info("Successfully got real data from Express.js scraper for: @{$cleanUsername}");
                    return $result;
                }

                Log::warning("Express.js scraper returned mock data, falling back to local methods");
            } catch (\Exception $e) {
                Log::warning("Express.js scraper failed, falling back to local methods: " . $e->getMessage());
            }

            // Check if token is properly configured for local methods
            if ($this->isDevMode()) {
                Log::warning("Using local mock data for Instagram scrape: @{$cleanUsername} (APIFY_TOKEN not configured)");
                return $this->generateMockData($cleanUsername, $startTime);
            }

            // Check if Node.js script exists
            if (!file_exists($this->nodeScriptPath)) {
                throw new \RuntimeException("Instagram scraper script not found: {$this->nodeScriptPath}");
            }

            // Prepare temporary output file
            $outputFile = storage_path("app/temp/instagram_{$cleanUsername}_" . time() . ".json");
            $this->ensureDirectoryExists(dirname($outputFile));

            // Build command
            $command = [
                'node',
                $this->nodeScriptPath,
                $cleanUsername,
                '--output',
                $outputFile,
                '--token',
                $this->apiToken
            ];

            Log::info("Executing command: " . implode(' ', array_map(fn($arg) =>
                str_contains($arg, $this->apiToken) ? '--token ***' : $arg, $command)));

            // Execute Node.js script
            $process = new Process($command);
            $process->setTimeout($this->timeout);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Read results from output file
            if (!file_exists($outputFile)) {
                throw new \RuntimeException("Output file not created: {$outputFile}");
            }

            $jsonContent = file_get_contents($outputFile);
            $data = json_decode($jsonContent, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \RuntimeException("Invalid JSON in output file: " . json_last_error_msg());
            }

            // Clean up temporary file
            @unlink($outputFile);

            // Add execution metadata
            $data['execution'] = [
                'php_execution_time' => round((microtime(true) - $startTime) * 1000, 2),
                'total_execution_time' => $data['metadata']['executionTime'] ?? 0,
                'executed_at' => now()->toISOString(),
                'via' => 'PHP + Node.js + Apify'
            ];

            Log::info("Successfully scraped Instagram profile: @{$cleanUsername}", [
                'followers' => $data['items']['followersCount'] ?? 'unknown',
                'engagement' => $data['analysis']['engagement_rate'] ?? 'unknown',
                'execution_time' => $data['execution']['php_execution_time'] . 'ms'
            ]);

            return $data;

        } catch (\Exception $e) {
            Log::error("Failed to scrape Instagram profile: @{$username}", [
                'error' => $e->getMessage(),
                'execution_time' => round((microtime(true) - $startTime) * 1000, 2) . 'ms'
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
                'metadata' => [
                    'scrapedAt' => now()->toISOString(),
                    'executionTime' => round((microtime(true) - $startTime) * 1000, 2),
                    'username' => $username
                ]
            ];
        }
    }

    /**
     * Scrape multiple Instagram profiles
     *
     * @param array $usernames Array of Instagram usernames
     * @param array $options Scraping options
     * @return array Results for all profiles
     */
    public function scrapeMultipleProfiles(array $usernames, array $options = []): array
    {
        $results = [];
        $batchSize = $options['batch_size'] ?? 3;
        $delayBetweenBatches = $options['delay_between_batches'] ?? 5; // seconds

        Log::info("Scraping multiple Instagram profiles", [
            'total' => count($usernames),
            'batch_size' => $batchSize
        ]);

        $batches = array_chunk($usernames, $batchSize);

        foreach ($batches as $batchIndex => $batch) {
            Log::info("Processing batch " . ($batchIndex + 1) . "/" . count($batches), [
                'usernames' => $batch
            ]);

            // Process batch sequentially to avoid overwhelming the API
            foreach ($batch as $username) {
                $results[$username] = $this->scrapeProfile($username, $options);

                // Small delay between individual requests
                if (next($batch) !== false) {
                    sleep(2);
                }
            }

            // Delay between batches
            if ($batchIndex < count($batches) - 1) {
                Log::info("Waiting {$delayBetweenBatches} seconds before next batch...");
                sleep($delayBetweenBatches);
            }
        }

        return [
            'success' => true,
            'total_profiles' => count($usernames),
            'successful_scrapes' => count(array_filter($results, fn($r) => $r['success'] ?? false)),
            'results' => $results,
            'scraped_at' => now()->toISOString()
        ];
    }

    /**
     * Get cached Instagram data or scrape if not cached
     *
     * @param string $username Instagram username
     * @param int $cacheMinutes Cache duration in minutes
     * @return array Instagram data
     */
    public function getOrScrapeProfile(string $username, int $cacheMinutes = 60): array
    {
        $cacheKey = "instagram_profile_{$username}";

        // Try to get from cache first
        $cached = cache()->get($cacheKey);
        if ($cached) {
            Log::info("Retrieved Instagram data from cache: @{$username}");
            return $cached;
        }

        // Not in cache, scrape fresh data
        $data = $this->scrapeProfile($username);

        // Cache successful results
        if ($data['success'] ?? false) {
            cache()->put($cacheKey, $data, now()->addMinutes($cacheMinutes));
            Log::info("Cached Instagram data for: @{$username}", [
                'cache_duration' => $cacheMinutes . ' minutes'
            ]);
        }

        return $data;
    }

    /**
     * Test the Instagram scraper setup
     *
     * @return array Test results
     */
    public function testSetup(): array
    {
        $tests = [];
        $allPassed = true;

        // Test 1: Check if Node.js is available
        try {
            $process = new Process(['node', '--version']);
            $process->run();
            $tests['node_js'] = [
                'passed' => $process->isSuccessful(),
                'message' => $process->isSuccessful()
                    ? "Node.js available: " . trim($process->getOutput())
                    : "Node.js not found: " . $process->getErrorOutput(),
            ];
            if (!$process->isSuccessful()) $allPassed = false;
        } catch (\Exception $e) {
            $tests['node_js'] = [
                'passed' => false,
                'message' => "Node.js check failed: " . $e->getMessage()
            ];
            $allPassed = false;
        }

        // Test 2: Check if scraper script exists
        $tests['scraper_script'] = [
            'passed' => file_exists($this->nodeScriptPath),
            'message' => file_exists($this->nodeScriptPath)
                ? "Scraper script found: " . $this->nodeScriptPath
                : "Scraper script missing: " . $this->nodeScriptPath
        ];
        if (!file_exists($this->nodeScriptPath)) $allPassed = false;

        // Test 3: Check if apify-client is installed
        try {
            $process = new Process(['npm', 'list', 'apify-client'], base_path());
            $process->run();
            $tests['apify_client'] = [
                'passed' => $process->isSuccessful(),
                'message' => $process->isSuccessful()
                    ? "apify-client package is installed"
                    : "apify-client package not found: " . $process->getErrorOutput()
            ];
            if (!$process->isSuccessful()) $allPassed = false;
        } catch (\Exception $e) {
            $tests['apify_client'] = [
                'passed' => false,
                'message' => "Package check failed: " . $e->getMessage()
            ];
            $allPassed = false;
        }

        // Test 4: Check temp directory permissions
        $tempDir = storage_path('app/temp');
        $this->ensureDirectoryExists($tempDir);
        $tests['temp_directory'] = [
            'passed' => is_writable($tempDir),
            'message' => is_writable($tempDir)
                ? "Temp directory writable: " . $tempDir
                : "Temp directory not writable: " . $tempDir
        ];
        if (!is_writable($tempDir)) $allPassed = false;

        return [
            'all_tests_passed' => $allPassed,
            'tests' => $tests,
            'recommendations' => $this->getSetupRecommendations($tests)
        ];
    }

    /**
     * Get setup recommendations based on test results
     *
     * @param array $tests Test results
     * @return array Recommendations
     */
    private function getSetupRecommendations(array $tests): array
    {
        $recommendations = [];

        if (!$tests['node_js']['passed']) {
            $recommendations[] = "Install Node.js: https://nodejs.org/";
        }

        if (!$tests['apify_client']['passed']) {
            $recommendations[] = "Install apify-client: run 'npm install apify-client' in project root";
        }

        if (!$tests['scraper_script']['passed']) {
            $recommendations[] = "Ensure instagram-scraper.js is in project root directory";
        }

        if (!$tests['temp_directory']['passed']) {
            $recommendations[] = "Create writable temp directory: " . storage_path('app/temp');
        }

        if (empty($recommendations)) {
            $recommendations[] = "All tests passed! Your Instagram scraper is ready to use.";
        }

        return $recommendations;
    }

    /**
     * Ensure directory exists
     *
     * @param string $directory Directory path
     * @return void
     */
    private function ensureDirectoryExists(string $directory): void
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Check if we're in development mode (no valid APIFY_TOKEN)
     *
     * @return bool
     */
    private function isDevMode(): bool
    {
        return empty($this->apiToken) ||
               $this->apiToken === 'your_apify_token_here' ||
               $this->apiToken === 'your_token_here' ||
               !str_starts_with($this->apiToken, 'apify_api_');
    }

    /**
     * Generate mock Instagram data for development
     *
     * @param string $username
     * @param float $startTime
     * @return array
     */
    private function generateMockData(string $username, float $startTime): array
    {
        // Base mock data patterns
        $mockProfiles = [
            'cristiano' => [
                'fullName' => 'Cristiano Ronaldo',
                'followersCount' => 623000000,
                'followingCount' => 534,
                'postsCount' => 3156,
                'engagementRate' => 3.2
            ],
            'abdennour_askri' => [
                'fullName' => 'Abdennour Askri',
                'followersCount' => 125000,
                'followingCount' => 892,
                'postsCount' => 287,
                'engagementRate' => 5.8
            ],
            'default' => [
                'fullName' => ucfirst($username),
                'followersCount' => rand(10000, 100000),
                'followingCount' => rand(200, 1000),
                'postsCount' => rand(50, 500),
                'engagementRate' => round(rand(20, 80) / 10, 1)
            ]
        ];

        $profile = $mockProfiles[$username] ?? $mockProfiles['default'];

        // Generate comprehensive mock data
        return [
            'success' => true,
            'message' => 'Profile data retrieved successfully (MOCK DATA)',
            'items' => [
                'id' => 'mock_' . $username,
                'username' => $username,
                'fullName' => $profile['fullName'],
                'biography' => "Mock biography for @{$username} - This is development mock data.",
                'followersCount' => $profile['followersCount'],
                'followingCount' => $profile['followingCount'],
                'postsCount' => $profile['postsCount'],
                'isVerified' => in_array($username, ['cristiano', 'leomessi', 'kimkardashian']),
                'isPrivate' => false,
                'profilePicUrl' => "data:image/svg+xml;base64," . base64_encode('
                    <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150">
                        <rect width="150" height="150" fill="#e9ecef"/>
                        <text x="75" y="80" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="#6c757d">' . strtoupper(substr($username, 0, 2)) . '</text>
                    </svg>'),
                'externalUrl' => null,
                'igtvVideoCount' => rand(0, 50),
                'highlightReelCount' => rand(5, 25)
            ],
            'analysis' => [
                'engagement_rate' => $profile['engagementRate'],
                'profile_score' => min(100, max(20, $profile['engagementRate'] * 15 + rand(10, 30))),
                'quality_analysis' => [
                    'grade' => $profile['engagementRate'] > 5 ? 'A' : ($profile['engagementRate'] > 3 ? 'B' : 'C'),
                    'authenticity_score' => rand(70, 95),
                    'activity_score' => rand(60, 90)
                ],
                'audience_insights' => [
                    'top_countries' => ['US', 'BR', 'IN', 'TR', 'GB'],
                    'age_groups' => [
                        '18-24' => rand(20, 40),
                        '25-34' => rand(25, 45),
                        '35-44' => rand(15, 35),
                        '45+' => rand(5, 20)
                    ],
                    'gender_split' => [
                        'male' => rand(40, 70),
                        'female' => rand(30, 60)
                    ]
                ],
                'influencer_tier' => $profile['followersCount'] > 1000000 ? 'Mega' :
                                   ($profile['followersCount'] > 100000 ? 'Macro' : 'Micro'),
                'estimated_earnings' => [
                    'post' => rand(100, 10000),
                    'story' => rand(50, 5000),
                    'reel' => rand(200, 15000)
                ]
            ],
            'recent_posts' => array_map(function($i) use ($username) {
                return [
                    'id' => 'mock_post_' . $i,
                    'shortcode' => 'mock_' . $username . '_' . $i,
                    'caption' => "Mock post #{$i} for @{$username}",
                    'likesCount' => rand(1000, 50000),
                    'commentsCount' => rand(50, 1000),
                    'timestamp' => now()->subDays(rand(1, 30))->toISOString(),
                    'imageUrl' => "data:image/svg+xml;base64," . base64_encode('
                        <svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400">
                            <rect width="400" height="400" fill="#f8f9fa"/>
                            <text x="200" y="210" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#6c757d">Post ' . $i . ' - @' . $username . '</text>
                        </svg>')
                ];
            }, range(1, 12)),
            'metadata' => [
                'executionTime' => rand(2000, 5000),
                'timestamp' => now()->toISOString(),
                'source' => 'mock_data_generator',
                'is_mock' => true
            ],
            'execution' => [
                'php_execution_time' => round((microtime(true) - $startTime) * 1000, 2),
                'total_execution_time' => rand(2000, 5000),
                'executed_at' => now()->toISOString(),
                'via' => 'PHP Mock Service (Development Mode)'
            ]
        ];
    }
}
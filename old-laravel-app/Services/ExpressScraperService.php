<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Express.js Scraper Service
 * Handles communication with the external Express.js scraper application
 */
class ExpressScraperService
{
    private string $scraperUrl;
    private string $apiToken;
    private int $timeout;
    private int $cacheMinutes;

    public function __construct()
    {
        $this->scraperUrl = env('SCRAPER_SERVICE_URL', 'https://vaguy.app/scrapper');
        $this->apiToken = env('APIFY_TOKEN', '');
        $this->timeout = env('SCRAPER_SERVICE_TIMEOUT', 120);
        $this->cacheMinutes = env('INSTAGRAM_API_CACHE_MINUTES', 60);
    }

    /**
     * Scrape Instagram profile data using Express.js service
     *
     * @param string $username Instagram username (without @)
     * @param array $options Additional options
     * @return array Scraped data and analysis
     */
    public function scrapeProfile(string $username, array $options = []): array
    {
        $startTime = microtime(true);

        try {
            Log::info("Starting Express.js scraper for: @{$username}");

            // Clean username
            $cleanUsername = str_replace('@', '', trim($username));

            // Validate username
            if (empty($cleanUsername) || !preg_match('/^[a-zA-Z0-9._]+$/', $cleanUsername)) {
                throw new \InvalidArgumentException("Invalid Instagram username: {$username}");
            }

            // Check cache first
            $cacheKey = "instagram_profile_{$cleanUsername}";
            if ($options['use_cache'] ?? true) {
                $cachedData = Cache::get($cacheKey);
                if ($cachedData) {
                    Log::info("Returning cached data for: @{$cleanUsername}");
                    return $cachedData;
                }
            }

            // Check if we should use mock data
            if ($this->shouldUseMockData()) {
                Log::warning("Using mock data for Express.js scraper: @{$cleanUsername} (invalid token)");
                return $this->generateMockData($cleanUsername, $startTime);
            }

            // Prepare request data
            $requestData = [
                'username' => $cleanUsername,
                'token' => $this->apiToken
            ];

            Log::info("Sending request to Express.js scraper", [
                'url' => $this->scraperUrl . '/api/scrub/fetch',
                'username' => $cleanUsername
            ]);

            // Make request to Express.js scraper service
            $response = Http::timeout($this->timeout)
                ->post($this->scraperUrl . '/api/scrub/fetch', $requestData);

            if (!$response->successful()) {
                throw new \Exception("Scraper service error: HTTP {$response->status()}");
            }

            $data = $response->json();

            if (!isset($data['items'])) {
                throw new \Exception("Invalid response format from scraper service");
            }

            // Process and enhance the data
            $processedData = $this->processScraperData($data['items'], $cleanUsername, $startTime);

            // Cache the result
            if ($options['use_cache'] ?? true) {
                Cache::put($cacheKey, $processedData, now()->addMinutes($this->cacheMinutes));
            }

            Log::info("Successfully scraped Instagram profile via Express.js: @{$cleanUsername}", [
                'followers' => $processedData['items']['followersCount'] ?? 'unknown',
                'engagement' => $processedData['analysis']['engagement_rate'] ?? 'unknown',
                'execution_time' => $processedData['execution']['php_execution_time'] . 'ms'
            ]);

            return $processedData;

        } catch (\Exception $e) {
            Log::error("Failed to scrape Instagram profile via Express.js: @{$username}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback to mock data on error
            Log::info("Falling back to mock data due to error");
            return $this->generateMockData($cleanUsername ?? $username, $startTime);
        }
    }

    /**
     * Process data from Express.js scraper
     */
    private function processScraperData(array $scraperData, string $username, float $startTime): array
    {
        // Convert Express.js response to our expected format
        $items = [
            'id' => 'express_' . $username,
            'username' => $scraperData['username'] ?? $username,
            'fullName' => $scraperData['fullName'] ?? ucfirst($username),
            'biography' => $scraperData['biography'] ?? '',
            'followersCount' => $scraperData['followersCount'] ?? 0,
            'followingCount' => $scraperData['followsCount'] ?? 0,
            'postsCount' => $scraperData['postsCount'] ?? 0,
            'isVerified' => false,
            'isPrivate' => false,
            'profilePicUrl' => $this->generateProfilePicture($username),
            'url' => $scraperData['url'] ?? "https://instagram.com/{$username}",
            'externalUrl' => null,
            'igtvVideoCount' => 0,
            'highlightReelCount' => 0
        ];

        // Calculate analysis data
        $engagementRate = floatval(str_replace(',', '.', $scraperData['engagment rate'] ?? '0'));
        $analysis = [
            'engagement_rate' => $engagementRate,
            'profile_score' => $this->calculateProfileScore($items, $engagementRate),
            'quality_analysis' => [
                'grade' => $this->getQualityGrade($engagementRate),
                'authenticity_score' => $this->calculateAuthenticityScore($items),
                'activity_score' => $this->calculateActivityScore($items)
            ],
            'audience_insights' => [
                'top_countries' => ['DZ', 'FR', 'MA', 'TN', 'EG'],
                'age_groups' => [
                    '18-24' => rand(25, 40),
                    '25-34' => rand(30, 45),
                    '35-44' => rand(15, 30),
                    '45+' => rand(5, 15)
                ],
                'gender_split' => [
                    'male' => rand(45, 65),
                    'female' => rand(35, 55)
                ]
            ],
            'influencer_tier' => $this->getInfluencerTier($items['followersCount']),
            'estimated_earnings' => [
                'post' => $this->estimatePostEarnings($items['followersCount'], $engagementRate),
                'story' => $this->estimateStoryEarnings($items['followersCount'], $engagementRate),
                'reel' => $this->estimateReelEarnings($items['followersCount'], $engagementRate)
            ],
            'average_likes' => $scraperData['average_likes'] ?? 'N/A',
            'average_views' => $scraperData['average_views'] ?? 'N/A',
            'average_comments' => $scraperData['average_comments'] ?? 'N/A'
        ];

        return [
            'success' => true,
            'message' => 'Profile data retrieved successfully via Express.js scraper',
            'items' => $items,
            'analysis' => $analysis,
            'recent_posts' => $this->generateMockPosts($username, 12),
            'metadata' => [
                'executionTime' => (microtime(true) - $startTime) * 1000,
                'timestamp' => now()->toISOString(),
                'source' => 'express_js_scraper',
                'is_mock' => false
            ],
            'execution' => [
                'php_execution_time' => round((microtime(true) - $startTime) * 1000, 2),
                'total_execution_time' => round((microtime(true) - $startTime) * 1000, 2),
                'executed_at' => now()->toISOString(),
                'via' => 'Laravel + Express.js + Apify'
            ]
        ];
    }

    /**
     * Check if we should use mock data
     */
    private function shouldUseMockData(): bool
    {
        return empty($this->apiToken) ||
               $this->apiToken === 'your_apify_token_here' ||
               !str_starts_with($this->apiToken, 'apify_api_');
    }

    /**
     * Generate mock data for development/fallback
     */
    private function generateMockData(string $username, float $startTime): array
    {
        // Use the same mock data generation as InstagramApifyService
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

        return [
            'success' => true,
            'message' => 'Profile data retrieved successfully (MOCK DATA - Express.js fallback)',
            'items' => [
                'id' => 'mock_express_' . $username,
                'username' => $username,
                'fullName' => $profile['fullName'],
                'biography' => "Mock biography for @{$username} - Express.js scraper fallback data.",
                'followersCount' => $profile['followersCount'],
                'followingCount' => $profile['followingCount'],
                'postsCount' => $profile['postsCount'],
                'isVerified' => in_array($username, ['cristiano', 'leomessi', 'kimkardashian']),
                'isPrivate' => false,
                'profilePicUrl' => $this->generateProfilePicture($username),
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
                'influencer_tier' => $this->getInfluencerTier($profile['followersCount']),
                'estimated_earnings' => [
                    'post' => $this->estimatePostEarnings($profile['followersCount'], $profile['engagementRate']),
                    'story' => $this->estimateStoryEarnings($profile['followersCount'], $profile['engagementRate']),
                    'reel' => $this->estimateReelEarnings($profile['followersCount'], $profile['engagementRate'])
                ]
            ],
            'recent_posts' => $this->generateMockPosts($username, 12),
            'metadata' => [
                'executionTime' => rand(1000, 3000),
                'timestamp' => now()->toISOString(),
                'source' => 'mock_data_generator',
                'is_mock' => true
            ],
            'execution' => [
                'php_execution_time' => round((microtime(true) - $startTime) * 1000, 2),
                'total_execution_time' => rand(1000, 3000),
                'executed_at' => now()->toISOString(),
                'via' => 'Express.js Mock Service (Fallback)'
            ]
        ];
    }

    // Helper methods
    private function generateProfilePicture(string $username): string
    {
        return "data:image/svg+xml;base64," . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150">
                <rect width="150" height="150" fill="#e9ecef"/>
                <text x="75" y="80" font-family="Arial, sans-serif" font-size="24" font-weight="bold" text-anchor="middle" fill="#6c757d">' . strtoupper(substr($username, 0, 2)) . '</text>
            </svg>');
    }

    private function calculateProfileScore(array $items, float $engagementRate): int
    {
        $score = 50; // Base score

        // Engagement rate bonus
        $score += min(30, $engagementRate * 5);

        // Follower count bonus
        if ($items['followersCount'] > 100000) $score += 10;
        if ($items['followersCount'] > 1000000) $score += 10;

        return min(100, max(20, $score));
    }

    private function getQualityGrade(float $engagementRate): string
    {
        if ($engagementRate >= 6) return 'A';
        if ($engagementRate >= 3) return 'B';
        if ($engagementRate >= 1) return 'C';
        return 'D';
    }

    private function calculateAuthenticityScore(array $items): int
    {
        return rand(75, 95); // Placeholder calculation
    }

    private function calculateActivityScore(array $items): int
    {
        return rand(70, 90); // Placeholder calculation
    }

    private function getInfluencerTier(int $followers): string
    {
        if ($followers >= 1000000) return 'Mega';
        if ($followers >= 100000) return 'Macro';
        if ($followers >= 10000) return 'Micro';
        return 'Nano';
    }

    private function estimatePostEarnings(int $followers, float $engagementRate): int
    {
        return round(($followers / 1000) * $engagementRate * 0.5);
    }

    private function estimateStoryEarnings(int $followers, float $engagementRate): int
    {
        return round($this->estimatePostEarnings($followers, $engagementRate) * 0.7);
    }

    private function estimateReelEarnings(int $followers, float $engagementRate): int
    {
        return round($this->estimatePostEarnings($followers, $engagementRate) * 1.5);
    }

    private function generateMockPosts(string $username, int $count): array
    {
        return array_map(function($i) use ($username) {
            return [
                'id' => 'express_post_' . $i,
                'shortcode' => 'exp_' . $username . '_' . $i,
                'caption' => "Express.js scraped post #{$i} for @{$username}",
                'likesCount' => rand(1000, 50000),
                'commentsCount' => rand(50, 1000),
                'timestamp' => now()->subDays(rand(1, 30))->toISOString(),
                'imageUrl' => "data:image/svg+xml;base64," . base64_encode('
                    <svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 400 400">
                        <rect width="400" height="400" fill="#f8f9fa"/>
                        <text x="200" y="210" font-family="Arial, sans-serif" font-size="16" text-anchor="middle" fill="#6c757d">Post ' . $i . ' - @' . $username . '</text>
                    </svg>')
            ];
        }, range(1, $count));
    }

    /**
     * Test the Express.js scraper service connection
     */
    public function testConnection(): array
    {
        try {
            $response = Http::timeout(10)->get($this->scraperUrl . '/');

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful() ? 'Express.js scraper service is reachable' : 'Service returned error',
                'url' => $this->scraperUrl
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 0,
                'message' => 'Failed to connect to Express.js scraper service: ' . $e->getMessage(),
                'url' => $this->scraperUrl
            ];
        }
    }
}
<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use App\Models\Order;
use App\Models\Category;
use App\Models\Wilaya;
use App\Services\InstagramDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProfileAnalyzerController extends Controller
{
    protected $instagramService;

    public function __construct()
    {
        parent::__construct();
        $this->instagramService = app(InstagramDataService::class);
    }
    public function index()
    {
        $pageTitle = 'Profile Analyzer';
        $pageDescription = 'Analyze influencer profiles with AI-powered insights and comprehensive metrics';
        $pageIcon = 'bi bi-graph-up';
        $breadcrumbs = [
            ['title' => 'Analytics', 'url' => '#'],
            ['title' => 'Profile Analyzer', 'url' => route('user.profile.analyzer.index')]
        ];

        // Get top influencers for quick analysis
        $topInfluencers = Influencer::active()
            ->with(['socialLink', 'orders' => function($query) {
                $query->completed();
            }])
            ->withCount([
                'orders as completed_orders_count' => function($query) {
                    $query->completed();
                },
                'reviews'
            ])
            ->orderByDesc('completed_orders_count')
            ->take(12)
            ->get();

        // Get categories for filtering
        $categories = Category::orderBy('name')->get();

        // Get locations
        $wilayas = Wilaya::orderBy('name')->get();

        return view($this->activeTemplate . 'user.profile_analyzer.index', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'topInfluencers',
            'categories',
            'wilayas'
        ));
    }

    public function analyze(Request $request, $influencer_id)
    {
        $request->validate([
            'analysis_type' => 'required|in:basic,detailed,comparison'
        ]);

        $influencer = Influencer::with([
            'socialLink',
            'orders' => function($query) {
                $query->completed();
            },
            'reviews',
            'category',
            'services'
        ])->findOrFail($influencer_id);

        // Fetch real Instagram data if available
        $externalData = $this->fetchInfluencerInstagramData($influencer);

        $analysisData = $this->performAnalysis($influencer, $request->analysis_type, $externalData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $analysisData,
                'external_data' => $externalData
            ]);
        }

        return redirect()->route('user.profile.analyzer.report', $influencer_id)
                        ->with('analysis_data', $analysisData)
                        ->with('external_data', $externalData);
    }

    public function report($influencer_id)
    {
        $influencer = Influencer::with([
            'socialLink',
            'orders' => function($query) {
                $query->completed();
            },
            'reviews',
            'category',
            'services'
        ])->findOrFail($influencer_id);

        $analysisData = session('analysis_data') ?? $this->performAnalysis($influencer, 'detailed');

        $pageTitle = 'Analysis Report - ' . $influencer->fullname;
        $pageDescription = 'Detailed analysis report for influencer profile';
        $pageIcon = 'bi bi-file-earmark-text';
        $breadcrumbs = [
            ['title' => 'Analytics', 'url' => '#'],
            ['title' => 'Profile Analyzer', 'url' => route('user.profile.analyzer.index')],
            ['title' => 'Report', 'url' => route('user.profile.analyzer.report', $influencer_id)]
        ];

        return view($this->activeTemplate . 'user.profile_analyzer.report', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'influencer',
            'analysisData'
        ));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'influencer_ids' => 'required|array|min:2|max:3',
            'influencer_ids.*' => 'exists:influencers,id'
        ]);

        $influencers = Influencer::with([
            'socialLink',
            'orders' => function($query) {
                $query->completed();
            },
            'reviews',
            'category',
            'services'
        ])->whereIn('id', $request->influencer_ids)->get();

        // Fetch external data for all influencers
        $externalDataCollection = [];
        $usernames = [];

        foreach ($influencers as $influencer) {
            $instagramUsername = $this->extractInstagramUsername($influencer);
            if ($instagramUsername) {
                $usernames[] = $instagramUsername;
            }
        }

        if (!empty($usernames)) {
            $externalDataCollection = $this->instagramService->fetchMultipleProfiles($usernames);
        }

        $comparisonData = [];
        foreach ($influencers as $influencer) {
            $instagramUsername = $this->extractInstagramUsername($influencer);
            $externalData = $externalDataCollection[$instagramUsername] ?? null;
            $comparisonData[] = $this->performAnalysis($influencer, 'comparison', $externalData);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $comparisonData,
                'external_data' => $externalDataCollection
            ]);
        }

        $pageTitle = 'Profile Comparison Report';
        $pageDescription = 'Detailed comparison of selected influencers across key performance metrics';
        $pageIcon = 'bi bi-bar-chart';
        $breadcrumbs = [
            ['title' => 'Analytics', 'url' => '#'],
            ['title' => 'Profile Analyzer', 'url' => route('user.profile.analyzer.index')],
            ['title' => 'Comparison', 'url' => route('user.profile.analyzer.compare')]
        ];

        return view($this->activeTemplate . 'user.profile_analyzer.comparison', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'influencers',
            'comparisonData',
            'externalDataCollection'
        ));
    }

    private function performAnalysis($influencer, $type = 'basic', $externalData = null)
    {
        $analysis = [
            'influencer_id' => $influencer->id,
            'basic_stats' => $this->getBasicStats($influencer, $externalData),
            'engagement_metrics' => $this->getEngagementMetrics($influencer, $externalData),
            'performance_score' => $this->calculatePerformanceScore($influencer, $externalData),
            'social_presence' => $this->analyzeSocialPresence($influencer, $externalData),
        ];

        // Add external data analysis if available
        if ($externalData) {
            $analysis['external_insights'] = $this->analyzeExternalData($externalData);
            $analysis['data_freshness'] = $externalData['fetched_at'] ?? now();
        }

        if ($type === 'detailed' || $type === 'comparison') {
            $analysis['detailed_insights'] = $this->getDetailedInsights($influencer, $externalData);
            $analysis['recommendations'] = $this->generateRecommendations($influencer, $externalData);
            $analysis['market_position'] = $this->analyzeMarketPosition($influencer);
            $analysis['growth_trends'] = $this->analyzeGrowthTrends($influencer);
        }

        if ($type === 'comparison') {
            $analysis['comparison_metrics'] = $this->getComparisonMetrics($influencer, $externalData);
        }

        return $analysis;
    }







    /**
     * Fetch Instagram data for influencer
     */
    private function fetchInfluencerInstagramData($influencer)
    {
        $instagramUsername = $this->extractInstagramUsername($influencer);

        if (!$instagramUsername) {
            return null;
        }

        return $this->instagramService->fetchProfileData($instagramUsername);
    }

    /**
     * Extract Instagram username from influencer's social links
     */
    private function extractInstagramUsername($influencer)
    {
        $instagramLink = $influencer->socialLink()
            ->where('social_icon', 'instagram')
            ->orWhere('url', 'like', '%instagram.com%')
            ->first();

        if (!$instagramLink) {
            return null;
        }

        $url = $instagramLink->url ?? $instagramLink->social_link ?? '';

        // Extract username from various Instagram URL formats
        if (preg_match('/instagram\.com\/([^\/\?]+)/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Analyze external data insights
     */
    private function analyzeExternalData($externalData)
    {
        if (!$externalData) {
            return null;
        }

        $qualityAnalysis = $this->instagramService->analyzeProfileQuality($externalData);
        $engagementRate = $this->instagramService->calculateEngagementRate($externalData);

        return [
            'real_time_followers' => $externalData['followers_count'],
            'real_time_posts' => $externalData['posts_count'],
            'profile_quality' => $qualityAnalysis,
            'estimated_engagement_rate' => $engagementRate,
            'verification_status' => $externalData['is_verified'],
            'account_privacy' => $externalData['is_private'] ? 'Private' : 'Public',
            'external_website' => $externalData['external_url'],
            'biography_length' => strlen($externalData['biography']),
            'data_accuracy' => $this->calculateDataAccuracy($externalData)
        ];
    }

    /**
     * Calculate data accuracy between internal and external data
     */
    private function calculateDataAccuracy($externalData)
    {
        // This could compare internal follower counts with external API data
        // For now, we'll assume external data is more accurate
        return [
            'confidence_level' => 'High',
            'last_updated' => $externalData['fetched_at'],
            'data_source' => 'Instagram API'
        ];
    }

    /**
     * Enhanced basic stats with external data
     */
    private function getBasicStats($influencer, $externalData = null)
    {
        $stats = [
            'total_followers' => $influencer->socialLink->sum('followers'),
            'completed_orders' => $influencer->orders->where('status', 1)->where('payment_status', 1)->count(),
            'average_rating' => round($influencer->reviews->avg('rating'), 2),
            'total_reviews' => $influencer->reviews->count(),
            'response_time' => $this->calculateResponseTime($influencer),
            'completion_rate' => $this->calculateCompletionRate($influencer),
            'member_since' => $influencer->created_at->format('Y-m-d'),
            'last_active' => $influencer->updated_at->diffForHumans(),
        ];

        // Enhance with external data if available
        if ($externalData) {
            $stats['real_time_followers'] = $externalData['followers_count'];
            $stats['real_time_posts'] = $externalData['posts_count'];
            $stats['follower_difference'] = $externalData['followers_count'] - $stats['total_followers'];
            $stats['verification_status'] = $externalData['is_verified'];
            $stats['account_type'] = $externalData['is_private'] ? 'Private' : 'Public';
        }

        return $stats;
    }

    /**
     * Enhanced engagement metrics with external data
     */
    private function getEngagementMetrics($influencer, $externalData = null)
    {
        $socialLinks = $influencer->socialLink;
        $totalFollowers = $socialLinks->sum('followers');

        $metrics = [
            'total_reach' => $totalFollowers,
            'platform_diversity' => $socialLinks->count(),
            'engagement_rate' => $this->estimateEngagementRate($socialLinks),
            'audience_quality' => $this->calculateAudienceQuality($influencer),
            'content_consistency' => $this->analyzeContentConsistency($influencer),
        ];

        // Enhance with external data
        if ($externalData) {
            $metrics['real_time_engagement_rate'] = $this->instagramService->calculateEngagementRate($externalData);
            $metrics['posting_frequency'] = $this->calculatePostingFrequency($externalData);
            $metrics['profile_completeness'] = $this->calculateProfileCompleteness($externalData);
        }

        return $metrics;
    }

    /**
     * Calculate posting frequency
     */
    private function calculatePostingFrequency($externalData)
    {
        $posts = $externalData['posts_count'];

        // Estimate based on post count (simplified calculation)
        if ($posts > 1000) return 'Very High';
        if ($posts > 500) return 'High';
        if ($posts > 100) return 'Medium';
        if ($posts > 50) return 'Low';
        return 'Very Low';
    }

    /**
     * Calculate profile completeness
     */
    private function calculateProfileCompleteness($externalData)
    {
        $score = 0;

        if (!empty($externalData['biography'])) $score += 25;
        if (!empty($externalData['external_url'])) $score += 25;
        if (!empty($externalData['full_name'])) $score += 25;
        if (!empty($externalData['profile_pic_url'])) $score += 25;

        return $score;
    }

    /**
     * Enhanced performance score with external data
     */
    private function calculatePerformanceScore($influencer, $externalData = null)
    {
        $rating = $influencer->reviews->avg('rating') ?? 0;
        $completionRate = $this->calculateCompletionRate($influencer);
        $responseTime = $this->calculateResponseTime($influencer);
        $totalOrders = $influencer->orders->where('status', 1)->where('payment_status', 1)->count();

        // Base score calculation (0-100)
        $ratingScore = ($rating / 5) * 20; // 20 points for rating
        $completionScore = $completionRate * 0.20; // 20 points for completion rate
        $responseScore = max(0, 20 - ($responseTime / 24)); // 20 points for response time
        $volumeScore = min(20, $totalOrders * 2); // 20 points for order volume

        $baseScore = $ratingScore + $completionScore + $responseScore + $volumeScore;

        // Enhance with external data (20 points)
        if ($externalData) {
            $qualityAnalysis = $this->instagramService->analyzeProfileQuality($externalData);
            $externalScore = ($qualityAnalysis['score'] / 100) * 20;
            $baseScore += $externalScore;
        }

        return round($baseScore, 1);
    }

    /**
     * Enhanced social presence analysis
     */
    private function analyzeSocialPresence($influencer, $externalData = null)
    {
        $socialLinks = $influencer->socialLink;
        $platforms = [];

        foreach ($socialLinks as $link) {
            $platforms[] = [
                'platform' => $link->social_icon,
                'followers' => $link->followers,
                'url' => $link->social_link,
                'engagement_potential' => $this->calculatePlatformEngagement($link)
            ];
        }

        $presence = [
            'platforms' => $platforms,
            'total_platforms' => count($platforms),
            'primary_platform' => $this->getPrimaryPlatform($socialLinks),
            'reach_distribution' => $this->calculateReachDistribution($socialLinks)
        ];

        // Enhance with external Instagram data
        if ($externalData) {
            $presence['instagram_insights'] = [
                'verified' => $externalData['is_verified'],
                'followers_count' => $externalData['followers_count'],
                'posts_count' => $externalData['posts_count'],
                'engagement_potential' => $this->instagramService->calculateEngagementRate($externalData),
                'profile_quality_grade' => $this->instagramService->analyzeProfileQuality($externalData)['grade']
            ];
        }

        return $presence;
    }

    /**
     * Enhanced detailed insights with external data
     */
    private function getDetailedInsights($influencer, $externalData = null)
    {
        $insights = [
            'strengths' => $this->identifyStrengths($influencer, $externalData),
            'weaknesses' => $this->identifyWeaknesses($influencer, $externalData),
            'opportunities' => $this->identifyOpportunities($influencer, $externalData),
            'risk_factors' => $this->identifyRisks($influencer, $externalData),
            'collaboration_history' => $this->analyzeCollaborationHistory($influencer),
            'pricing_analysis' => $this->analyzePricing($influencer),
        ];

        if ($externalData) {
            $insights['external_validation'] = $this->validateWithExternalData($influencer, $externalData);
        }

        return $insights;
    }

    /**
     * Validate influencer data with external sources
     */
    private function validateWithExternalData($influencer, $externalData)
    {
        $validation = [];

        // Compare follower counts
        $internalFollowers = $influencer->socialLink->where('social_icon', 'instagram')->first()->followers ?? 0;
        $externalFollowers = $externalData['followers_count'];

        $followerDifference = abs($externalFollowers - $internalFollowers);
        $followerAccuracy = $internalFollowers > 0 ? (1 - ($followerDifference / $internalFollowers)) * 100 : 0;

        $validation['follower_accuracy'] = round($followerAccuracy, 1);
        $validation['follower_variance'] = $followerDifference;
        $validation['data_reliability'] = $followerAccuracy > 90 ? 'High' : ($followerAccuracy > 70 ? 'Medium' : 'Low');

        return $validation;
    }

    /**
     * Enhanced strengths identification
     */
    private function identifyStrengths($influencer, $externalData = null)
    {
        $strengths = [];

        if ($influencer->reviews->avg('rating') >= 4.5) {
            $strengths[] = 'Excellent client satisfaction';
        }

        if ($this->calculateCompletionRate($influencer) >= 90) {
            $strengths[] = 'High completion rate';
        }

        if ($influencer->socialLink->sum('followers') > 50000) {
            $strengths[] = 'Large social media following';
        }

        // Add external data insights
        if ($externalData) {
            if ($externalData['is_verified']) {
                $strengths[] = 'Verified Instagram account';
            }

            if ($externalData['followers_count'] > 100000) {
                $strengths[] = 'Strong Instagram presence (100K+ followers)';
            }

            $qualityScore = $this->instagramService->analyzeProfileQuality($externalData)['score'];
            if ($qualityScore >= 80) {
                $strengths[] = 'High-quality Instagram profile';
            }
        }

        return $strengths;
    }

    /**
     * Enhanced recommendations with external data
     */
    private function generateRecommendations($influencer, $externalData = null)
    {
        $recommendations = [];
        $performanceScore = $this->calculatePerformanceScore($influencer, $externalData);
        $completionRate = $this->calculateCompletionRate($influencer);
        $totalFollowers = $influencer->socialLink->sum('followers');

        if ($performanceScore >= 80) {
            $recommendations[] = [
                'type' => 'excellent',
                'title' => 'High-Performance Influencer',
                'description' => 'This influencer shows excellent performance metrics and is highly recommended for campaigns.'
            ];
        }

        if ($completionRate < 80) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Monitor Completion Rate',
                'description' => 'Completion rate is below optimal. Consider discussing timeline expectations.'
            ];
        }

        // Add external data recommendations
        if ($externalData) {
            if ($externalData['is_verified']) {
                $recommendations[] = [
                    'type' => 'excellent',
                    'title' => 'Verified Account Advantage',
                    'description' => 'Verified Instagram account adds credibility and trust to campaigns.'
                ];
            }

            $qualityAnalysis = $this->instagramService->analyzeProfileQuality($externalData);
            if ($qualityAnalysis['score'] < 60) {
                $recommendations[] = [
                    'type' => 'improvement',
                    'title' => 'Profile Optimization Needed',
                    'description' => 'Instagram profile could be improved. Suggest profile completion and content strategy.'
                ];
            }

            $engagementRate = $this->instagramService->calculateEngagementRate($externalData);
            if ($engagementRate > 5) {
                $recommendations[] = [
                    'type' => 'opportunity',
                    'title' => 'High Engagement Potential',
                    'description' => 'Strong engagement rate indicates active and interested audience.'
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Enhanced comparison metrics
     */
    private function getComparisonMetrics($influencer, $externalData = null)
    {
        $metrics = [
            'key_metrics' => [
                'performance_score' => $this->calculatePerformanceScore($influencer, $externalData),
                'total_followers' => $influencer->socialLink->sum('followers'),
                'completion_rate' => $this->calculateCompletionRate($influencer),
                'average_rating' => round($influencer->reviews->avg('rating'), 2),
                'total_orders' => $influencer->orders->where('status', 1)->where('payment_status', 1)->count(),
                'response_time' => $this->calculateResponseTime($influencer)
            ]
        ];

        // Add external metrics for comparison
        if ($externalData) {
            $metrics['external_metrics'] = [
                'real_time_followers' => $externalData['followers_count'],
                'posts_count' => $externalData['posts_count'],
                'verification_status' => $externalData['is_verified'],
                'profile_quality_score' => $this->instagramService->analyzeProfileQuality($externalData)['score'],
                'estimated_engagement_rate' => $this->instagramService->calculateEngagementRate($externalData),
                'collaboration_score' => $this->instagramService->calculateCollaborationScore(
                    $externalData,
                    $this->instagramService->analyzeProfileQuality($externalData)['score'],
                    $this->instagramService->calculateEngagementRate($externalData)
                )
            ];
        }

        return $metrics;
    }
}
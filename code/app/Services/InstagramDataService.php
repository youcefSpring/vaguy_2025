<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class InstagramDataService
{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = 'https://crm.vaguy.app/api/scrub/fetch';
        $this->token = env('APIFY_TOKEN');
    }

    /**
     * Fetch Instagram profile data from external API
     */
    public function fetchProfileData($username)
    {
        try {
            if (empty($this->token)) {
                throw new Exception('APIFY_TOKEN not configured');
            }

            $response = Http::timeout(30)->post($this->apiUrl, [
                'username' => $username,
                'token' => $this->token
            ]);

            if (!$response->successful()) {
                throw new Exception('API request failed with status: ' . $response->status());
            }

            $data = $response->json();

            if (!isset($data['items'])) {
                throw new Exception('Invalid API response structure');
            }

            return $this->transformData($data['items']);

        } catch (Exception $e) {
            Log::error('Instagram API Error: ' . $e->getMessage(), [
                'username' => $username,
                'token_exists' => !empty($this->token)
            ]);

            return null;
        }
    }

    /**
     * Transform API response to match our expected format
     */
    protected function transformData($apiData)
    {
        return [
            'username' => $apiData['username'] ?? '',
            'full_name' => $apiData['fullName'] ?? '',
            'followers_count' => $apiData['followersCount'] ?? 0,
            'following_count' => $apiData['followingCount'] ?? 0,
            'posts_count' => $apiData['postsCount'] ?? 0,
            'biography' => $apiData['biography'] ?? '',
            'external_url' => $apiData['externalUrl'] ?? '',
            'is_verified' => $apiData['isVerified'] ?? false,
            'is_private' => $apiData['isPrivate'] ?? false,
            'profile_pic_url' => $apiData['profilePicUrl'] ?? '',
            'category' => $apiData['category'] ?? '',
            'fetched_at' => now()
        ];
    }

    /**
     * Get multiple profiles data
     */
    public function fetchMultipleProfiles($usernames)
    {
        $results = [];

        foreach ($usernames as $username) {
            $data = $this->fetchProfileData($username);
            if ($data) {
                $results[$username] = $data;
            }
        }

        return $results;
    }

    /**
     * Calculate engagement rate based on profile data
     */
    public function calculateEngagementRate($profileData)
    {
        if (!$profileData || !isset($profileData['followers_count']) || $profileData['followers_count'] == 0) {
            return 0;
        }

        // Estimated engagement rate based on followers count
        // This is a simplified calculation - in a real scenario you'd need post engagement data
        $followers = $profileData['followers_count'];

        if ($followers < 1000) {
            return rand(80, 100) / 10; // 8-10%
        } elseif ($followers < 10000) {
            return rand(60, 80) / 10; // 6-8%
        } elseif ($followers < 100000) {
            return rand(40, 60) / 10; // 4-6%
        } elseif ($followers < 1000000) {
            return rand(20, 40) / 10; // 2-4%
        } else {
            return rand(10, 20) / 10; // 1-2%
        }
    }

    /**
     * Analyze profile quality based on external data
     */
    public function analyzeProfileQuality($profileData)
    {
        if (!$profileData) {
            return ['score' => 0, 'factors' => []];
        }

        $score = 0;
        $factors = [];

        // Verification status (20 points)
        if ($profileData['is_verified']) {
            $score += 20;
            $factors[] = 'Verified account';
        }

        // Biography completeness (15 points)
        if (!empty($profileData['biography']) && strlen($profileData['biography']) > 20) {
            $score += 15;
            $factors[] = 'Complete biography';
        }

        // External URL (10 points)
        if (!empty($profileData['external_url'])) {
            $score += 10;
            $factors[] = 'External website link';
        }

        // Public account (10 points)
        if (!$profileData['is_private']) {
            $score += 10;
            $factors[] = 'Public account';
        }

        // Follower count (25 points)
        $followers = $profileData['followers_count'];
        if ($followers > 100000) {
            $score += 25;
            $factors[] = 'High follower count (100K+)';
        } elseif ($followers > 10000) {
            $score += 20;
            $factors[] = 'Good follower count (10K+)';
        } elseif ($followers > 1000) {
            $score += 15;
            $factors[] = 'Moderate follower count (1K+)';
        } elseif ($followers > 100) {
            $score += 10;
            $factors[] = 'Growing follower count (100+)';
        }

        // Posting activity (20 points)
        $posts = $profileData['posts_count'];
        if ($posts > 500) {
            $score += 20;
            $factors[] = 'Very active poster (500+ posts)';
        } elseif ($posts > 100) {
            $score += 15;
            $factors[] = 'Active poster (100+ posts)';
        } elseif ($posts > 50) {
            $score += 10;
            $factors[] = 'Regular poster (50+ posts)';
        } elseif ($posts > 10) {
            $score += 5;
            $factors[] = 'Some posting activity (10+ posts)';
        }

        return [
            'score' => min(100, $score),
            'factors' => $factors,
            'grade' => $this->getQualityGrade($score)
        ];
    }

    /**
     * Get quality grade based on score
     */
    protected function getQualityGrade($score)
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B+';
        if ($score >= 60) return 'B';
        if ($score >= 50) return 'C+';
        if ($score >= 40) return 'C';
        if ($score >= 30) return 'D';
        return 'F';
    }

    /**
     * Compare profiles and generate insights
     */
    public function compareProfiles($profilesData)
    {
        if (empty($profilesData)) {
            return [];
        }

        $comparison = [];

        foreach ($profilesData as $username => $data) {
            $quality = $this->analyzeProfileQuality($data);
            $engagement = $this->calculateEngagementRate($data);

            $comparison[$username] = [
                'profile_data' => $data,
                'quality_score' => $quality['score'],
                'quality_grade' => $quality['grade'],
                'quality_factors' => $quality['factors'],
                'estimated_engagement_rate' => $engagement,
                'reach_potential' => $this->calculateReachPotential($data),
                'collaboration_score' => $this->calculateCollaborationScore($data, $quality['score'], $engagement)
            ];
        }

        return $comparison;
    }

    /**
     * Calculate reach potential
     */
    protected function calculateReachPotential($profileData)
    {
        $followers = $profileData['followers_count'];

        if ($followers > 1000000) return 'Very High';
        if ($followers > 100000) return 'High';
        if ($followers > 10000) return 'Medium';
        if ($followers > 1000) return 'Low';
        return 'Very Low';
    }

    /**
     * Calculate collaboration score
     */
    public function calculateCollaborationScore($profileData, $qualityScore, $engagementRate)
    {
        $score = 0;

        // Quality weight (40%)
        $score += ($qualityScore * 0.4);

        // Engagement weight (30%)
        $score += ($engagementRate * 10 * 0.3);

        // Followers influence (20%)
        $followers = $profileData['followers_count'];
        if ($followers > 100000) $score += 20;
        elseif ($followers > 10000) $score += 15;
        elseif ($followers > 1000) $score += 10;
        else $score += 5;

        // Account type bonus (10%)
        if ($profileData['is_verified']) $score += 10;
        if (!$profileData['is_private']) $score += 5;

        return min(100, round($score));
    }
}
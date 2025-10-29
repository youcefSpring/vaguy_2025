<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\InstagramDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScrubController extends Controller
{
    protected $instagramService;

    public function __construct()
    {
        parent::__construct();
        $this->instagramService = app(InstagramDataService::class);
    }

    public function index()
    {
        $pageTitle = 'Profile Scraper';
        $pageDescription = 'Analyze Instagram profiles and get detailed insights using real-time data';
        $pageIcon = 'bi bi-search';
        $breadcrumbs = [
            ['title' => 'Analytics', 'url' => '#'],
            ['title' => 'Profile Scraper', 'url' => route('user.scrub.index')]
        ];

        // Get the API token from environment
        $token = env('APIFY_TOKEN', '');

        return view($this->activeTemplate . 'user.scrub.index', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'token'
        ));
    }

    public function fetch(\App\Http\Requests\InstagramScrubRequest $request)
    {

        try {
            $profileData = $this->instagramService->fetchProfileData($request->username);

            if (!$profileData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch profile data. Please check the username and try again.',
                    'items' => null
                ], 400);
            }

            // Log successful scraping
            Log::info('Profile scraped successfully', [
                'username' => $request->username,
                'followers' => $profileData['followers_count'] ?? 0
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile data fetched successfully',
                'items' => $profileData
            ]);

        } catch (\Exception $e) {
            Log::error('Profile scraping failed', [
                'username' => $request->username,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch profile data. Please try again later.',
                'items' => null
            ], 500);
        }
    }
}
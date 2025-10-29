<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Models\SocialLink;

class ScrapController extends Controller
{

    public function get_instagram_user_names(){
        $usernames=SocialLink::whereHas('influencer')
                            ->where('url','like','https://www.instagram.com/%')
                            ->whereNot('url','like','https://www.instagram.com/p%')->pluck('url');
       
         $new=[];
            foreach ($usernames as $url) {
                // Remove the base URL part to get the username or path
                $username = str_replace('https://www.instagram.com/', '', $url);
    
                // Optionally, remove the trailing slash if there is one
                $username = rtrim($username, '/');
               $new[]=  $username;
                
            }

        return $new;
    }
    public function scrap_fb(){
    

// Path to the image you want to perform OCR on
$imagePath = asset('assets/images/user/profile/63ab2a0f910681672161807.png');

// Create a new instance of TesseractOCR and specify the image path
$ocr = new TesseractOCR($imagePath);

// Run OCR on the image
$text = $ocr->run();

// Output the extracted text
echo $text;

    }

    public function scrapeProfileInstagram()
    {
        $token=env('APIFY_TOKEN');
        return view($this->activeTemplate . 'user.scrub', compact('token'));
    }
}

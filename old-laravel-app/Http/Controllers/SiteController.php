<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Frontend;
use App\Models\Hiring;
use App\Models\HiringConversation;
use App\Models\Influencer;
use App\Models\InfluencerCategory;
use App\Models\Language;
use App\Models\Order;
use App\Models\OrderConversation;
use App\Models\Page;
use App\Models\Review;
use App\Models\Service;
use App\Models\ServiceTag;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use App\Models\Tag;
use App\Models\Wilaya;
use App\Models\SocialLink;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions ;
use JoelButcher\Facebook\Facebook;
use Phpfastcache\Helper\Psr16Adapter;
use Daaner\TikTok\Models\UserInfo;
use App\Models\Favorite;
// use Illuminate\Support\Facades\Http;
use File;
use ZipArchive;
use Response;
use App\Models\Statistic;
use App\Models\CampainInfluencerOffer;
use Illuminate\Support\Facades\Redirect;
use  App\Services\EmailService;
use App\Jobs\SendEmailJob;
class SiteController extends Controller {

    public function send_campain_email_notification($details,$destinations){
        $destinations = 'y.brnabderrezak@univ-boumerdes.dz';
        $details = [
            'subject' => 'test mailing in vaguy',
            'body' => 'test mailing in vaguy',
            'title' => 'test mailing in vaguy'
        ];

        // Dispatch the job
        dispatch(new SendEmailJob($details, $destinations));

        return 'Email job has been dispatched!';
    }
    public function change(){
        return 'changer la langue';
    }

    public function index() {
        //   return CampainInfluencerOffer::get();
        // return redirect()->route('user.login');
        $pageTitle = 'Home';
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', '/')->first();
        $tags      = Tag::withCount('serviceTag')->orderBy('service_tag_count', 'desc')->take(6)->get();
        return view($this->activeTemplate . 'home', compact('pageTitle', 'sections', 'tags'));
    }

    public function get_facebook_data()
    {
        $fb = new \JoelButcher\Facebook\Facebook([
            'app_id' => '896107898040364',
            'app_secret' => 'ec009d3e05e1563bd597b9e79cd22c90',
            'default_graph_version' => 'v2.10',
            ]);

            $helper = $fb->getRedirectLoginHelper();



          try {
            // Returns a `Facebook\Response` object
                $response = $fb->get('/me?fields=id,name', ['access_token'=>'ec009d3e05e1563bd597b9e79cd22c90']);
          } catch(Facebook\Exception\ResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
          } catch(Facebook\Exception\SDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
          }

          $user = $response->getGraphUser();

          dd($user);
          // Array access
          echo 'Name: ' . $user['name'];
          // or get
          echo 'Name: ' . $user->getName();
    }

     public function get_youtube_data(){
        $key  = 'AIzaSyBM1cZpGoe6hWeo-x-7U-9YOHrgieRkLUc';
        $url  = 'https://www.googleapis.com/youtube/v3/';
        $channel_id='UCvMKMjxlKiz_9F6ycYZXThw';
        $client   = new Client(['base_uri' => $url]);
        // $client   =  Http::dd()->get('https://www.youtube.com/@drayadachannel7193');
        // dd($client);
        $response = $client->get('channels', [
          RequestOptions::QUERY => [
          "part" => "snippet,statistics,topicDetails",
          "id" => $channel_id, // channel user name or can be id channel id
          "key" => $key // you app key from google https://console.cloud.google.com/apis/library
          ]
        ]);

        $data = json_decode($response->getBody());
        echo "<pre>";
        dd($data);
     }

    public function pages($slug) {
        $page      = Page::where('tempname', $this->activeTemplate)->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections  = $page->secs;
        return view($this->activeTemplate . 'pages', compact('pageTitle', 'sections'));
    }

    public function contact() {
        $pageTitle = "Contact Us";
        return view($this->activeTemplate . 'contact', compact('pageTitle', 'sections'));
        $sections  = Page::where('tempname', $this->activeTemplate)->where('slug', 'contact')->first();
        return view($this->activeTemplate . 'contact', compact('pageTitle', 'sections'));
    }

    public function login() {
        $pageTitle    = "Login";
        $loginContent = Frontend::where('data_keys', 'login.content')->first();
        return view($this->activeTemplate . 'login', compact('pageTitle', 'loginContent'));
    }

    public function contactSubmit(Request $request) {

        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $request->session()->regenerateToken();

        $random = getNumber();

        $ticket           = new SupportTicket();
        $ticket->user_id  = auth()->id() ?? 0;

        if(!auth()->id()){
            $ticket->influencer_id = authInfluencerId()??0;
        }

        $ticket->name     = $request->name;
        $ticket->email    = $request->email;
        $ticket->priority = 2;

        $ticket->ticket     = $random;
        $ticket->subject    = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status     = 0;
        $ticket->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title     = 'A new support ticket has opened ';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message                    = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message           = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        if(auth()->user()){
            $view = 'ticket.view';
        }elseif(authInfluencer()){
            $view = 'influencer.ticket.view';
        }else{
            $view = 'ticket.view';
        }

        return to_route($view, [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug, $id) {
        $policy    = Frontend::where('id', $id)->where('data_keys', 'policy_pages.element')->firstOrFail();
        // return $policy;
        $pageTitle = $policy->data_values->title;
        return view($this->activeTemplate . 'policy', compact('policy', 'pageTitle'));
        return view($this->activeTemplate . 'policy', compact('policy', 'pageTitle'));
    }

    public function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();

        if (!$language) {
            $lang = 'fr'; // Français par défaut
        }

        // Store in session
        session()->put('lang', $lang);

        // Set the application locale
        app()->setLocale($lang);

        return back();
    }

    public function cookieAccept() {
        $general = gs();
        Cookie::queue('gdpr_cookie', $general->site_name, 43200);
        return back();
    }

    public function cookiePolicy() {
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        return view($this->activeTemplate . 'cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null) {
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font') . DIRECTORY_SEPARATOR . 'RobotoMono-Regular.ttf';
        $fontSize  = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) {
            $fontSize = 9;
        }

        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function maintenance() {
        $pageTitle = 'Maintenance Mode';
        $general   = gs();

        if ($general->maintenance_mode == 0) {
            return to_route('home');
        }

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view($this->activeTemplate . 'maintenance', compact('pageTitle', 'maintenance'));
    }

    public function services(Request $request) {
        if($request->method() === 'POST'){
            return redirect()->route('services', [
                "search" => $request->search,
                "sort" => $request->sort,
                "max" => $request->max,
                "min" => $request->min ,
                "tagId" => $request->tagId,
                "categories" => $request->categories
            ]);
        }
        $pageTitle   = trans('خدمات');
        $services    = $this->getServices($request);
        $allCategory = Category::active()->orderBy('name')->get();
        $sections    = Page::where('tempname', $this->activeTemplate)->where('slug', 'service')->first();
        return view($this->activeTemplate . 'service.list', compact('services', 'pageTitle', 'allCategory', 'sections'));
    }
    public function services2(Request $request) {
        $pageTitle   = trans('خدمات');
        $services    = $this->getServices($request);
        $allCategory = Category::active()->orderBy('name')->get();
        $sections    = Page::where('tempname', $this->activeTemplate)->where('slug', 'service')->first();
        return view($this->activeTemplate . 'service.list', compact('services', 'pageTitle', 'allCategory', 'sections'));
        return view($this->activeTemplate . 'service.list', compact('services', 'pageTitle', 'allCategory', 'sections'));
    }

    public function serviceByTag(Request $request, $id, $name) {
        $pageTitle = 'Service - ' . $name;

        $serviceId = collect(ServiceTag::where('tag_id', $id)->pluck('service_id'))->toArray();
        $orders    = array_map(function ($item) {
            return "id = {$item} desc";
        }, $serviceId);
        $rawOrder    = implode(', ', $orders);
        $services    = Service::approved()->whereIn('id', $serviceId)->orderByRaw($rawOrder)->with('influencer', 'category')->paginate(getPaginate());
        $allCategory = Category::active()->orderBy('name')->get();

        $sections = Page::where('tempname', $this->activeTemplate)->where('slug', 'service')->first();
        return view($this->activeTemplate . 'service.list', compact('services', 'pageTitle', 'id', 'sections', 'allCategory'));
    }

    public function filterService(Request $request) {
        $services = $this->getServices($request);
        $allCategory = Category::active()->orderBy('name')->get();
        return view($this->activeTemplate . 'service.filtered', compact('services'));
        return view($this->activeTemplate . 'service.filtered', compact('services'));
    }

    protected function getServices($request) {

        $services = Service::approved();

        if ($request->categories) {
            $services = $services->whereIn('category_id', $request->categories);
        }

        if ($request->tagId) {
            $serviceId = collect(ServiceTag::where('tag_id', $request->tagId)->pluck('service_id'))->toArray();
            $services  = $services->whereIn('id', $serviceId);
        }

        if ($request->min && $request->max) {
            $min      = intval($request->min);
            $max      = intval($request->max);
            $services = $services->whereBetween('price', [$min, $max]);
        }

        if ($request->sort) {
            // dd($request->sort);
            // $sort     = explode('_', $request->sort);
            // $services = $services->orderBy(@$sort[0], @$sort[1]);

            $services = $services->orderBy("price",$request->sort);
        }

        if ($request->search) {
            $search   = $request->search;
            $services = $services->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', '%' . $search . '%')->orWhere('description', 'LIKE', '%' . $search . '%');
            })->orWhereHas('category', function ($category) use ($search) {
                $category->where('name', 'like', "%$search%");
            });
        }


        return $services->latest()->with('influencer', 'category')->paginate(getPaginate(15));
    }

    public function serviceDetails($slug, $id, $orderId = 0) {

        if ($orderId) {
            $order = Order::completed()
                          ->where('user_id', auth()->id())
                          ->where('service_id', $id)
                          ->findOrFail($orderId);
        }

        $service         = Service::approved()
                                  ->where('id', $id)
                                  ->with('category', 'influencer.socialLink', 'gallery', 'reviews.user', 'tags')
                                  ->firstOrFail();
        $pageTitle       = 'تفاصيل الخدمة';
        $customPageTitle = $service->title;

        $anotherServices = Service::approved()
                                  ->where('influencer_id', $service->influencer->id)
                                  ->where('id', '!=', $id)
                                  ->with('influencer')
                                  ->latest()
                                  ->take(4)
                                  ->get() ;

        // Define SEO contents
        $seoContents['keywords']           = $service->meta_keywords ?? [];
        $seoContents['social_title']       = $service->title;
        $seoContents['description']        = strLimit(strip_tags($service->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($service->description), 150);
        $seoContents['image']              = getImage(getFilePath('service') . '/' . $service->image, getFileSize('service'));
        $seoContents['image_size']         = getFileSize('service');

        return view($this->activeTemplate . 'service.detail', compact('service', 'pageTitle', 'anotherServices', 'seoContents', 'orderId', 'customPageTitle'));
        $seoContents['keywords']           = $service->meta_keywords ?? [];
        $seoContents['social_title']       = $service->title;
        $seoContents['description']        = strLimit(strip_tags($service->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($service->description), 150);
        $seoContents['image']              = getImage(getFilePath('service') . '/' . $service->image, getFileSize('service'));
        $seoContents['image_size']         = getFileSize('service');

        return view($this->activeTemplate . 'service.detail', compact('service', 'pageTitle', 'anotherServices', 'seoContents', 'orderId', 'customPageTitle'));
    }


    public function influencerProfile($name, $id) {
        $influencer              = Influencer::active()->with('education', 'qualification', 'services.category')->findOrFail($id);
        $statistics              = Statistic::where('influencer_id', $id)->get();

        // Extract statistics by platform
        $statistic = $statistics->where('platform', 'facebook')->first();
        $statistic_instagram = $statistics->where('platform', 'instagram')->first();
        $statistic_tiktok = $statistics->where('platform', 'tiktok')->first();
        $statistic_youtube = $statistics->where('platform', 'youtube')->first();

        $pageTitle               = 'Influencer Profile';
        $reviews                 = Review::where('influencer_id', $id)->where('order_id', 0)->with('user')->latest()->paginate(10);

        $data['ongoing_job']    = Order::inprogress()->where('influencer_id', $id)->count() + Hiring::inprogress()->where('influencer_id', $id)->count();
        $data['completed_job']  = Order::completed()->where('influencer_id', $id)->count() + Hiring::completed()->where('influencer_id', $id)->count();;
        $data['queue_job']      = Order::whereIn('status', [2, 3])->where('influencer_id', $id)->count() + Hiring::whereIn('status', [2, 3])->where('influencer_id', $id)->count();
        $data['pending_job']    = Order::pending()->where('influencer_id', $id)->count() + Hiring::pending()->where('influencer_id', $id)->count();
        $pending_job  = Order::pending()->where('influencer_id', $id)->count() + Hiring::pending()->where('influencer_id', $id)->count();

        return view($this->activeTemplate . 'influencer.profile', compact('pageTitle', 'influencer', 'statistics', 'statistic', 'statistic_instagram', 'statistic_tiktok', 'statistic_youtube', 'data', 'reviews'));
    }
    public function influencers(Request $request) {
        $pageTitle = 'Find Influencers';
        $pageDescription = 'Discover and connect with talented influencers for your campaigns';
        $pageIcon = 'bi bi-people';
        $breadcrumbs = [
            ['title' => 'Discover', 'url' => '#'],
            ['title' => 'Influencers', 'url' => route('influencers')]
        ];
        $pageActions = '<a href="' . route('user.profile.analyzer.index') . '" class="btn btn-outline-primary">
                           <i class="bi bi-graph-up me-1"></i>' . __('Profile Analyzer') . '
                       </a>';

        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $wilayas   = Wilaya::get(['id','code','name']);
        $influencers = $this->getInfluencer($request);

        // Always ensure we have influencers data - if empty, load all active influencers
        if (!$influencers || ($influencers->total() == 0 && !$this->hasFilters($request))) {
            $influencers = Influencer::active()
                ->with('socialLink')
                ->orderBy('completed_order', 'desc')
                ->paginate(getPaginate(20));
        }

        $sections    = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        $allCategory = Category::active()->orderBy('name')->get();

        if($request->method() === 'POST'){

           return redirect()->route('influencers', [
                                                           'social'=> $request->social,
                                                           'average_interactions' => $request->average_interactions,
                                                           'wilaya_audience' => $request->wilaya_audience,
                                                           'gender_audience' => $request->gender_audience,
                                                           'audience_age' => $request->audience_age,
                                                           'gender_influencers' => $request->gender_influencers,
                                                           'lang' => $request->lang,
                                                           'followers_min' => $request->followers_min,
                                                           'followers_max' => $request->followers_max,
                                                           'age' => $request->age,
                                                           'category' => $request->age,
                                                           'categoryId' => $request->categoryId,
                                                           'search' => $request->search,
                                                           'wilaya' => $request->wilaya,
                                                           'rating' => $request->rating,
                                                           'sort' => $request->sort,
                                                           'gender' => $request->gender,
                                                           'completedJob' => $request->completedJob
                                                        ]);
        }

        // Handle AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'influencers' => $influencers,
                'wilayas' => $wilayas,
                'countries' => $countries,
                'categories' => $allCategory
            ]);
        }

        return view($this->activeTemplate . 'influencers', compact(
            'influencers',
            'wilayas',
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'pageActions',
            'sections',
            'countries',
            'allCategory'
        ));
    }

    public function filterInfluencers(Request $request)
    {
        $keys = ['social', 'average_interactions', 'wilaya_audience', 'gender_audience', 'audience_age', 'gender_influencers', 'lang', 'followers_min', 'followers_max', 'age', 'category', 'categoryId', 'search', 'wilaya', 'rating', 'sort', 'gender', 'completedJob'];
        $requestData = $this->extractRequestVariables($request, $keys);

        // Now you can use $requestData array to filter influencers
    }
    public function extractRequestVariables(Request $request, array $keys)
{
    $data = [];
    foreach ($keys as $key) {
        if ($request->has($key)) {
            $data[$key] = $request->$key;
        }
    }
    return $data;
}

    public function influencerByCategory(Request $request, $id, $name) {

        $pageTitle    = 'Category - ' . $name;
        $countries    = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $influencerId = InfluencerCategory::where('category_id', $id)->select('influencer_id')->get();
        $influencers  = Influencer::active()->whereIn('id', $influencerId)->with('socialLink')->latest()->paginate(getPaginate(15));
        $sections     = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        $wilayas   = Wilaya::get(['id','code','name']);
        return view($this->activeTemplate . 'influencers', compact('influencers', 'wilayas','pageTitle', 'sections', 'countries', 'id'));
    }

    public function influencers2(Request $request) {
        // dd($request->attributes);

        $pageTitle   = 'Influencers';
        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $wilayas   = Wilaya::get(['id','code','name']);
        $influencers = $this->getInfluencer($request);
        //  dd($influencers);
        $sections    = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        $allCategory = Category::active()->orderBy('name')->get();


        return view($this->activeTemplate . 'influencers', compact('influencers','wilayas', 'pageTitle', 'sections', 'countries', 'allCategory'));

  }

    public function filterInfluencer(Request $request) {
        $influencers = $this->getInfluencer($request);
        $influencersId    = Favorite::where('user_id', auth()->id())
                                   ->select('influencer_id')
                                   ->pluck('influencer_id')
                                   ->toArray();


        return view($this->activeTemplate . 'filtered_influencer', compact('influencers'));
    }

    protected function getInfluencer($request) {
        $influencers = Influencer::active();
        if (isset($request->social)) {
            $s = is_array($request->social) ? $request->social : [$request->social];
            if (count($s) > 0) {
                //  dd($s);
                $influencerId = SocialLink::where(function ($query) use ($s) {
                    $query->orWhere(function ($query) use ($s) {
                        foreach ($s as $value) {
                            $query->orWhere('social_icon', 'like', "%$value%");
                        }
                    });
                })
                ->pluck('influencer_id')
                ->toArray();

            $influencerId_statistics = Statistic::whereIn('social', $s)
                                                ->pluck('influencer_id')
                                                ->toArray();

            $influencerId = array_merge($influencerId, $influencerId_statistics);
            // dd($influencerId);
            $influencers  = $influencers->whereIn('id', $influencerId);
                //   dd($influencers->all());
            }


        }
        // return $influencers;
        if (isset($request->average_interactions) ) {
            $s=$request->average_interactions;
            $influencerId = Statistic::where('average_interactions', $s)
                                      ->pluck('influencer_id')
                                      ->toArray();

            $influencers  = $influencers->whereIn('id', $influencerId);
            // dd($influencers->all());
            // dd(2);
        }


        if (isset($request->wilaya_audience)) {
            $s = is_array($request->wilaya_audience) ? $request->wilaya_audience : [$request->wilaya_audience];
            if (count($s) > 0) {
                // dd($request);
                    $influencerId = Statistic::whereIn('city_1',$s)
                                            ->orWhereIn('city_2',$s)
                                            ->orWhereIn('city_3',$s)
                                            ->orWhereIn('city_4',$s)
                                            ->pluck('influencer_id')->toArray();
                //   dd($influencerId);

                if(isset($request->wilaya_interactions_pourcentage)){
                           $wip=$request->wilaya_interactions_pourcentage;
                    $newinfluencerId = Statistic::where(function($query) use ($wip,$s){
                                                 $query->where('nomber_followers_1','>=',$wip)
                                                ->whereIn('city_1',$s);
                                            })
                                       ->orWhere(function($query) use ($wip,$s){
                                        $query->where('nomber_followers_2','>=',$wip)
                                       ->whereIn('city_2',$s);
                                   })
                                   ->orWhere(function($query) use ($wip,$s){
                                    $query->where('nomber_followers_3','>=',$wip)
                                   ->whereIn('city_3',$s);
                               })
                               ->orWhere(function($query) use ($wip,$s){
                                $query->where('nomber_followers_4','>=',$wip)
                               ->whereIn('city_4',$s);
                                })
                                ->pluck('influencer_id')->toArray();
                    $influencerId=array_intersect($influencerId,$newinfluencerId);
                    // return $influencerId;
                }
                $influencers  = $influencers->whereIn('id', $influencerId);
                // dd(3);
            }
        }
        if (isset($request->gender_audience) ) {
            $w=$request->gender_audience;
// return $w;
                $w=str_replace('a','e',$w);
                // dd($w);
            if(isset($request->gender_audience_pourcentage)){
                $influencerId = Statistic::whereNotNull('gender_'.$w)->Where('gender_'.$w,'>',$request->gender_audience_pourcentage)
                ->pluck('influencer_id')->toArray();
            }else{
                $influencerId = Statistic::whereNotNull('gender_'.$w)->Where('gender_'.$w,'>',0)
                ->pluck('influencer_id')->toArray();
            }

            $influencers  = $influencers->whereIn('id', $influencerId);
            // dd(4);
        }

        if (isset($request->audience_age) ) {
            $w=$request->audience_age;

            //create column query age_g_13 age_w_13 age_m_13
             if (isset($request->gender_audience) )
             {
               if($request->gender_audience =="men"){
                 $w='age_m_'.$w;
               }
               else{
                 $w='age_w_'.$w;
               }
             }else{
                 $w='age_g_'.$w;
             }
// return $audience_age_pourcentage;
            if(isset($request->audience_age_pourcentage)){
                $influencerId = Statistic::whereNotNull($w)
                                         ->Where($w,'>=',$request->audience_age_pourcentage)
                                         ->pluck('influencer_id')->toArray();
            }else
            {
            $influencerId = Statistic::whereNotNull($w)->Where($w,'>=',0)
                                      ->pluck('influencer_id')->toArray();
            }
            $influencers  = $influencers->whereIn('id', $influencerId);
            // dd(5);
        }

        if (isset($request->gender_influencers)) {
            $g=$request->gender_influencers;
                $influencers = $influencers->where('gender', $g);
                // dd(6);
        }
        if (isset($request->lang)) {
            $s = is_array($request->lang) ? $request->lang : [$request->lang];
            if (count($s) > 0) {
                $influencers=$influencers->where('languages','like','%'.$s[0].'%' );
                foreach ($s as $statement) {
                    $influencers=$influencers->orWhere('languages','like','%'.$statement.'%' );
                }
            }
            // dd(7);
        }

        if ($request->followers_min && $request->followers_max) {
            $min_f = $request->followers_min;
            $max_f = $request->followers_max;
            $influencerId = SocialLink::whereBetween('followers',[$min_f,$max_f])->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
            // dd(8);
            // dd($request);
        }

        if ($request->age) {
            // return now()->subYears($request->age);
            $influencers  = $influencers->whereBetween('birth_day',[now()->subYears($request->age), now()->subYears($request->age-5)]);
            // whereBetween('birth_day', [now()->subYears(5), now()])->get();
            // dd(9);
        }
        // return ($request);
        if ($request->category) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $influencerId = InfluencerCategory::whereIn('category_id', $categories)->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
            // dd(10);
        }

        if ($request->categoryId) {
            $influencerId = InfluencerCategory::where('category_id', $request->categoryId)->select('influencer_id')->get();
            $influencers  = $influencers->whereIn('id', $influencerId);
            // dd(11);
        }

        if ($request->search) {
            $search      = $request->search;
            $influencers = $influencers->where(function ($query) use ($search) {
                $query->where('firstname', "LIKE", "%$search%")
                    ->orWhere('lastname', 'LIKE', "%%$search")
                    ->orWhere('username', 'LIKE', "%$search%")
                    ->orWhere('profession', 'LIKE', "%$search%");
            });
            // dd(12);
        }

           // if ($request->country) {
          //     $influencers = $influencers->whereJsonContains('address', ['country' => $request->country]);
          // }
        if ($request->wilaya) {
               if(is_array($request->wilaya)){
                $influencers = $influencers->whereIn('address->state',$request->wilaya);
               }else{
                $influencers = $influencers->where('address->state',$request->wilaya);
               }
            //    dd(13);
        }

        if ($request->rating) {
            $influencers = $influencers->where('rating', '>=', $request->rating);
            // dd(14);
        }

        if ($request->sort == 'top_rated') {
            $influencers = $influencers->where('completed_order', '>', 0)->orderBy('completed_order', 'desc');
        }

        if (isset($request->gender)) {
            $g=$request->gender;
            // if($g != 'all')
            // {
                $influencers = $influencers->where('gender', $g);
           //     }

            //     else{
           //         $influencers = $influencers->whereIn('gender', ['man','woman']);
            //     }
            // dd(15);
        }


        if ($request->completedJob) {
            $influencers = $influencers->where('completed_order', '>', $request->completedJob)->orderBy('completed_order', 'desc');
        }

        $favoriteInfluencersIds = Favorite::where('user_id', auth()->id())
                                   ->pluck('influencer_id')
                                   ->toArray();

// Add is_favorite attribute to each influencer object
$influencers->each(function ($influencer) use ($favoriteInfluencersIds) {
    $influencer->is_favorite = in_array($influencer->id, $favoriteInfluencersIds) ? true : false;
});
// dd($influencers);


        return $influencers->with('socialLink')->orderBy('completed_order', 'desc')->paginate(getPaginate(20));;
    }

    private function hasFilters($request) {
        $filterParams = [
            'search', 'social', 'average_interactions', 'wilaya_audience',
            'gender_audience', 'audience_age', 'gender_influencers', 'lang',
            'followers_min', 'followers_max', 'age', 'category', 'categoryId',
            'wilaya', 'rating', 'sort', 'gender', 'completedJob'
        ];

        foreach ($filterParams as $param) {
            if ($request->has($param) && !empty($request->$param)) {
                return true;
            }
        }

        return false;
    }

    protected function filterInfluencer_new(Request $request) {
        // dd($request);
        //  $this->influencers($request);
        return Redirect::to('/getinf?' . http_build_query($request->all()));
        $countries   = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $wilayas   = Wilaya::get(['id','code','name']);
        $sections    = Page::where('tempname', $this->activeTemplate)->where('slug', 'influencers')->first();
        $allCategory = Category::active()->orderBy('name')->get();
        $pageTitle   = 'Influencers';
        // $influencers=$influencers->with('socialLink')->orderBy('completed_order', 'desc')->paginate(getPaginate(18));
        $influencers=$this->getInfluencer($request);
        // dd($influencers);

        return view('templates.basic.influencers',compact('influencers','countries','wilayas','pageTitle','sections','allCategory'));
    }



    public function attachmentDownload($attachment, $conversation_id, $type) {
        if($type == 'order'){
            OrderConversation::where('id',$conversation_id)->firstOrFail();
        }elseif($type == 'hiring'){
            HiringConversation::where('id',$conversation_id)->firstOrFail();
        }else{
            ConversationMessage::where('id',$conversation_id)->firstOrFail();
        }
        $path = getFilePath('conversation');
        $file = $path . '/' . $attachment;
        return response()->download($file);
    }


    public function getFile($filename){
        // $vars=explode('/',$filename);
        // // dd($vars);
        // $fnmane=$vars[2];
        // // dd(1);
        // // $file = public_path().$filename;
        // $file = public_path()."/".$filename;

        // // $headers = array('Content-Type: application/*');
        // // if(file_exists($file))
        // // {
        // return Response::download($file);
        return response()->download(public_path($filename));
        // }
        // else{
        //     return redirect()->back()->with('error','support introuvable !!');
        // }


    }

    public function download_influencer_file($id){
         $influencer=Influencer::find($id);
         $files=json_decode($influencer->stat);

        //  return response()->download(public_path($files[0]));
        //return $files[0];
		// if(isset($files)){
        //  foreach($files as $f ){
        //  return response()->download(public_path($f));

        //     // $this->getFile($files[0]);
        //  }
		// }
		// else{
		// 	return redirect()->back();
		// }
        try{
        // $zip      = new ZipArchive;
        // $fileName = $influencer->firstname.'.zip';
        // if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE) {
        //   $files = File::files(public_path('influencers/stat/'.$influencer->id));
        // //   $files=json_decode($influencer->stat);
        //   foreach ($files as $key => $value) {
        //     $relativeName = basename($value);
        //     $zip->addFile($value, $relativeName);
        //   }
        //   $zip->close();
        $zip = new ZipArchive;
    $fileName = $influencer->firstname . '.zip';
    $zipFilePath = public_path($fileName);

    if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
        // Get the path to the directory containing the files
        $directory = public_path('influencers/stat/' . $influencer->id);

        // Open the directory
        $dirHandle = opendir($directory);

        // Iterate over the files in the directory
        while (false !== ($file = readdir($dirHandle))) {
            if ($file != "." && $file != "..") {
                // Add each file to the zip archive
                $zip->addFile($directory . '/' . $file, $file);
            }
        }

        // Close the directory handle
        closedir($dirHandle);

        // Close the zip archive
        $zip->close();
        }
        return response()->download(public_path($fileName));
       }
       catch (\Exception $e){
        return back();
       }
    //    $files = File::files(public_path('influencers/stat/'.$influencer->id));
    //    foreach ($files as $key => $value) {
    //    if(File::exists(public_path($value))){
    //     File::delete(public_path($value));
    //    }

    //        }
    //        $influencer->stat=null;

    }
}

<?php

use App\Lib\GoogleAuthenticator;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use Illuminate\Support\Facades\Cache;
use App\Notify\Notify;
use Illuminate\Support\Str;


function status_to_letters($status){
    switch ($status){
        case 1 :
            return "Accepté"; break;
        case 0 :
            return "Refusé"; break;
        case 3 :
                return "Confirmed"; break;
        case 4 :
                return "Job done"; break;
        case 5 :
                return "Terminé"; break;
        case 6 :
                return "Reported to admin"; break;
        default :
        return "En attente";
    }
}

 function testwilaya($inf,$array)
{
    return in_array(extract_string_between_two_string($inf->address, '"state":"', '","zip"'),$array);

}
 function contains_words($str, $words) {
    foreach ($words as $word) {
        $found = false;
        for ($i = 0; $i <= strlen($str) - strlen($word); $i++) {
            $j = 0;
            while ($j < strlen($word) && $str[$i + $j] == $word[$j]) {
                $j++;
            }
            if ($j == strlen($word)) {
                $found = true;
                break;
            }
        }
        if (!$found) {
            return false;
        }
    }
    return true;
}


 function extract_string_between_two_string($str, $starting_word, $ending_word)
{
$subtring_start = strpos($str, $starting_word);
//Adding the starting index of the starting word to
//its length would give its ending index
$subtring_start += strlen($starting_word);
//Length of our required sub string
$size = strpos($str, $ending_word, $subtring_start) - $subtring_start;
// Return the substring from the index substring_start of length size
return substr($str, $subtring_start, $size);
}




// function get_facebook_acces_token($app_id,$app_secret)
// {
//     $response = Http::get('https://graph.facebook.com/oauth/access_token?client_id='.$app_id.'&client_secret='.$app_secret.'&grant_type=client_credentials');
//         // return bcrypt('azerty');
//         $r=json_decode($response,true);
//         return $r;
// }


if (!function_exists('getDetails')) {

    function getDetails($pageUrl) {
            $url = $pageUrl;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_REFERER, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            $result = curl_exec($ch);
            curl_close($ch);

            // dd ($result);
            $output=[];
            $metaPos = strpos($result, "<meta content=");
            if($metaPos != false)
            {
                $meta = substr($result ,$metaPos,70);

                //meghdare followers
                $followerPos = strpos($meta , "Followers");
                $followers = substr($meta , 15 , $followerPos-15);
                $output[0] = $followers;

                //meghdare followings
                // $commaPos = strpos($meta , ',');
                $followingPos = strpos($meta, 'Following');
                $followings = substr($meta , $followerPos+10 , $followingPos - ($followerPos+10));
                $output[1] = $followings;


                //meghdare posts
                $seccondCommaPos = $followingPos + 10;
                $postsPos = strpos($meta, 'Post');
                $posts = substr($meta, $seccondCommaPos , $postsPos - $seccondCommaPos);
                $output[2] = $posts;

                  //image finder
                    $imgPos = strpos($result, "og:image");
                    $image = substr($result , $imgPos+19 , 200);
                    $endimgPos = strpos($image, "/>");
                    $finalImagePos = substr($result, $imgPos+19 , $endimgPos-2);
                    $output[3] = $finalImagePos;

                return $output;
            }
            else
            {
                return null;
            }
        }
    }

function systemDetails()
{
    $system['name'] = 'vaguy';
    $system['version'] = '.beta';
    $system['build_version'] = '4.2.5';
    return $system;
}

function slug($string)
{
    return Illuminate\Support\Str::slug($string);
}

function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


function activeTemplate($asset = false)
{
    $general = gs();
    $template = $general->active_template;
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $general = gs();
    $template = $general->active_template;
    return $template;
}

function loadReCaptcha()
{
    return Captcha::reCaptcha();
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#003')
{
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function verifyCaptcha()
{
    return Captcha::verify();
}

function loadExtension($key)
{
    $analytics = Extension::where('act', $key)->where('status', 1)->first();
    return $analytics ? $analytics->generateScript() : '';
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getAmount($amount, $length = 2)
{
    $amount = round($amount, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false)
{
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);

    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        }
    }

    return $printAmount;
}


function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet)
{
    return "https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8";
}


function keyToTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}

function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}

function getIpInfo()
{
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}

function osBrowser()
{
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}

function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = 'https://license.viserlab.com/updates/templates/' . systemDetails()['name'];
    $response = CurlRequest::curlPostContent($url, $param);
    if ($response) {
        return $response;
    } else {
        return null;
    }
}


function getPageSections($arr = false)
{
    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}


function getImage($image, $size = null, $isAvatar = false)
{
    $clean = '';
    // if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    // }
    if ($isAvatar) {
        return asset('assets/avatar.png');
    }
    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('assets/images/default.png');
}


function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true)
{
    $general = gs();
    $globalShortCodes = [
        'site_name' => $general->site_name,
        'site_currency' => $general->cur_text,
        'currency_symbol' => $general->cur_sym,
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);

    $notify = new Notify($sendVia);
    $notify->templateName = $templateName;
    $notify->shortCodes = $shortCodes;
    $notify->user = $user;
    $notify->createLog = $createLog;
    $notify->userColumn = getColumnName($user);
    $notify->send();
}

function getColumnName($user)
{
    $array = explode("\\", get_class($user));
    return strtolower(end($array)) . '_id';
}

function getPaginate($paginate = 20)
{
    return $paginate;
}

function paginateLinks($data)
{
    return $data->appends(request()->all())->links();
}


function menuActive($routeName, $type = null)
{
    if ($type == 3) {
        $class = 'side-menu--open';
    } elseif ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } elseif (request()->routeIs($routeName)) {
        return $class;
    }
}


function fileUploader($file, $location, $size = null, $old = null, $thumb = null)
{
    $fileManager = new FileManager($file);
    $fileManager->path = $location;
    $fileManager->size = $size;
    $fileManager->old = $old;
    $fileManager->thumb = $thumb;
    $fileManager->upload();
    return $fileManager->filename;
}

function fileManager()
{
    return new FileManager();
}

function getFilePath($key)
{
    return fileManager()->$key()->path;
}

function getFileSize($key)
{
    return fileManager()->$key()->size;
}
function getFileThumb($key)
{
    return fileManager()->$key()->thumb;
}

function getFileExt($key)
{
    return fileManager()->$key()->extensions;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d h:i A')
{
    $lang = session()->get('lang');
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false)
{
    if ($singleQuery) {
        $content = Frontend::where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::query();
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}


function gatewayRedirectUrl($type = false)
{
    if ($type) {
        return 'user.deposit.history';
    } else {
        return 'user.deposit';
    }
}

function verifyG2fa($user, $code, $secret = null)
{
    $authenticator = new GoogleAuthenticator();
    if (!$secret) {
        $secret = $user->tsc;
    }
    $oneCode = $authenticator->getCode($secret);
    $userCode = $code;
    if ($oneCode == $userCode) {
        $user->tv = 1;
        $user->save();
        return true;
    } else {
        return false;
    }
}


function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);
    return $path;
}


function showMobileNumber($number)
{
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email)
{
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}


function getRealIP()
{
    $ip = $_SERVER["REMOTE_ADDR"];
    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}


function appendQuery($key, $value)
{
    return request()->fullUrlWithQuery([$key => $value]);
}


function dateSort($a, $b)
{
    return strtotime($a) - strtotime($b);
}

function dateSorting($arr)
{
    usort($arr, "dateSort");
    return $arr;
}

function showRatings($rating)
{
    $ratings = '';
    if ($rating > 0) {
        $avgRating = $rating;
        $integerVal = floor($avgRating);
        $fraction = $avgRating - $integerVal;

        if ($fraction < .25) {
            $avgRating = intval($avgRating);
        }
        if ($fraction > .75) {
            $avgRating = intval($avgRating) + 1;
        }
        for ($i = 1; $i <= $avgRating; $i++) {
            $ratings .= '<i class="las la-star"></i>';
        }
        if ($fraction > .25 && $fraction < .75) {
            $avgRating += 1;
            $ratings .= '<i class="las la-star-half-alt"></i>';
        }
    } else {
        $avgRating = 0;
    }
    $nonStar = 5 - intval($avgRating);
    for ($k = 1; $k <= $nonStar; $k++) {
        $ratings .= '<i class="lar la-star"></i>';
    }
    return $ratings;
}

function br2nl($description)
{
    return preg_replace('/<br\\s*?\/??>/i', '', $description);
}


function authInfluencer()
{
    return auth()->guard('influencer')->user();
}

function authInfluencerId()
{
    return auth()->guard('influencer')->id();
}

function gs()
{
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }
    return $general;
}

if (!function_exists('shortNumber')) {
    function shortNumber($number)
    {
        if ($number < 1000) {
            return number_format($number);
        } elseif ($number < 1000000) {
            return number_format($number / 1000, 1) . 'K';
        } elseif ($number < 1000000000) {
            return number_format($number / 1000000, 1) . 'M';
        } else {
            return number_format($number / 1000000000, 1) . 'B';
        }
    }
}

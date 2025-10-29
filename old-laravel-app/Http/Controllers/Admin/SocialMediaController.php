<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function index(Request $request){
        // return $username;
    	$res=getDetails('https://instagram.com/'.$request->username);
    	return $res;
    }


    public function getInfo(Request $request,$username){
        // return $username;
    	$res=getDetails('https://instagram.com/'.$username);

        // dd($res);
    	return $res;
    }
}

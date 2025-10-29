<?php

namespace App\Http\Controllers\Influencer\Auth;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use App\Models\InfluencerPasswordReset;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('influencer.guest');
        $this->activeTemplate = activeTemplate();
    }


    public function showLinkRequestForm()
    {
        $pageTitle = "Account Recovery";
        return view($this->activeTemplate . 'influencer.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'value'=>'required'
        ]);
        $fieldType = $this->findFieldType();
        $influencer = Influencer::where($fieldType, $request->value)->first();

        if (!$influencer) {
            $notify[] = ['error', 'Couldn\'t find any account with this information'];
            return back()->withNotify($notify);
        }

        InfluencerPasswordReset::where('email', $influencer->email)->delete();
        $code = verificationCode(6);
        $password = new InfluencerPasswordReset();
        $password->email = $influencer->email;
        $password->token = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        $influencerIpInfo = getIpInfo();
        $influencerBrowserInfo = osBrowser();
        notify($influencer, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => @$influencerBrowserInfo['os_platform'],
            'browser' => @$influencerBrowserInfo['browser'],
            'ip' => @$influencerIpInfo['ip'],
            'time' => @$influencerIpInfo['time']
        ],['email']);

        $email = $influencer->email;
        session()->put('pass_res_mail',$email);
        $notify[] = ['success', 'Password reset email sent successfully'];
        return to_route('influencer.password.code.verify')->withNotify($notify);
    }

    public function findFieldType()
    {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }

    public function codeVerify(){
        $pageTitle = 'Verify Email';
        $email = session()->get('pass_res_mail');
        if (!$email) {
            $notify[] = ['error','Oops! session expired'];
            return to_route('influencer.password.request')->withNotify($notify);
        }
        return view($this->activeTemplate.'influencer.auth.passwords.code_verify',compact('pageTitle','email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required'
        ]);
        $code =  str_replace(' ', '', $request->code);

        if (InfluencerPasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify[] = ['error', 'Verification code doesn\'t match'];
            return to_route('influencer.password.request')->withNotify($notify);
        }
        $notify[] = ['success', 'You can change your password.'];
        session()->flash('fpass_email', $request->email);
        return to_route('influencer.password.reset', $code)->withNotify($notify);
    }
}

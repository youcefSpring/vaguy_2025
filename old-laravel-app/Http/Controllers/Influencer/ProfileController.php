<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Models\Influencer;
use App\Models\InfluencerEducation;
use App\Models\InfluencerQualification;
use App\Models\Order;
use App\Models\Category;
use App\Models\Service;
use App\Models\SocialLink;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Wilaya;
class ProfileController extends Controller {

    public function profile() {
        $pageTitle    = "Profile Setting";
        $influencer   = Influencer::where('id', authInfluencerId())->with('education', 'qualification', 'socialLink', 'categories')->firstOrFail();
        $categories = Category::all();
        $wilayas = Wilaya::all();
        $languageData = ["Arabic","French","English"];

        $countries    = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $data['ongoing_orders']   = Order::inprogress()->where('influencer_id', $influencer->id)->count();
        $data['completed_orders'] = Order::completed()->where('influencer_id', $influencer->id)->count();
        $data['pending_orders']   = Order::pending()->where('influencer_id', $influencer->id)->count();
        $data['total_services']   = Service::where('status', 1)->where('influencer_id', $influencer->id)->count();

        return view('templates.basic.influencer.profile.profile', compact('pageTitle', 'influencer', 'categories', 'wilayas', 'languageData', 'countries', 'data'));
    }

    public function returnToProfile(){
        return redirect()->route('influencer.profile.setting.index');
    }

    public function submitProfile(Request $request) {
        $request->validate([
            'firstname'  => 'required|string',
            'lastname'   => 'required|string',
            'profession' => 'nullable|max:40|string',
            'summary'    => 'nullable|string',
            'image'      => ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
        ], [
            'firstname.required' => 'First name field is required',
            'lastname.required'  => 'Last name field is required',
        ]);

        $influencer = authInfluencer();

        if ($request->hasFile('image')) {
        //     try {
        //         $old               = $influencer->image;
        //         //dimension of photo
        //         // $a=getFileSize('influencerProfile');
        //         // return $a;
        //         $siza="600x600";
        //         // $influencer->image = fileUploader($request->image, getFilePath('influencerProfile'), getFileSize('influencerProfile'), $old);
        //         $influencer->image = fileUploader($request->image, getFilePath('influencerProfile'), $siza, $old);
        //     } catch (\Exception$exp) {
        //         $notify[] = ['error', 'Couldn\'t upload your image'];
        //         return $this->returnToProfile();
        //         return back()->withNotify($notify);
        //     }


        try {
            $destinationPath =  getFilePath('influencerProfile');

            // Ensure the destination directory exists
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file=$request->image;

            // Generate a unique file name
            $fileName = uniqid() . '_' . $file->getClientOriginalName();

            // Move the file to the public/attachments directory
            $file->move($destinationPath, $fileName);

            // Add the relative file path to the array
            // $arrFile[] = $fileName;
            // dd(1);
            $influencer->image = $fileName;
            $influencer->save();
            // $service->image = fileUploader($request->image, getFilePath('service'), getFileSize('service'), $oldImage, getFileThumb('service'));
        } catch (\Exception$exp) {
            $notify[] = ['error', 'Couldn\'t upload your image'];
            return back()->withNotify($notify);
        }
         }
        $influencer->firstname = $request->firstname;
        $influencer->lastname  = $request->lastname;

        $influencer->address = [
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'country' => @$influencer->address->country,
            'city'    => $request->city,
        ];

        $influencer->profession = $request->profession;
        $influencer->summary    = nl2br($request->summary);

        if ($request->category) {
            $categoriesId = $request->category;
            // dd(gettype($categoriesId[0]));

             // Cast each element to integer
             $categoriesId = array_map('intval', $categoriesId);
            //  dd($categoriesId);
            $influencer->categories()->sync($categoriesId);
        }

        $influencer->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function submitSkill(Request $request) {

        $request->validate([
            "skills"   => "nullable|array",
            "skills.*" => "required|string",
        ]);

        $influencer         = authInfluencer();
        $influencer->skills = $request->skills;
        $influencer->save();

        $notify[] = ['success', 'Skill added successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function addLanguage(Request $request) {

        $request->validate([
            'name'      => 'required|string|max:40',
            'listening' => 'required|in:Basic,Medium,Fluent',
            'speaking'  => 'required|in:Basic,Medium,Fluent',
            'writing'   => 'required|in:Basic,Medium,Fluent',
        ]);

        $influencer   = authInfluencer();
        $oldLanguages = authInfluencer()->languages ?? [];

        $addedLanguages = array_keys($oldLanguages);

        if (in_array(strtolower($request->name), array_map('strtolower', $addedLanguages))) {
            $notify[] = ['error', $request->name . ' already added'];
            return $this->returnToProfile();
            return back()->withNotify($notify);
        }

        $newLanguage[$request->name] = [
            'listening' => $request->listening,
            'speaking'  => $request->speaking,
            'writing'   => $request->writing,
        ];

        $languages = array_merge($oldLanguages, $newLanguage);

        $influencer->languages = $languages;
        $influencer->save();

        $notify[] = ['success', 'Language added successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function removeLanguage($language) {
        $influencer     = authInfluencer();
        $oldLanguages   = $influencer->languages ?? [];
        $addedLanguages = array_keys($oldLanguages);

        if (in_array($language, $addedLanguages)) {
            unset($oldLanguages[$language]);
        }

        $influencer->languages = $oldLanguages;
        $influencer->save();

        $notify[] = ['success', 'Language removed successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function addSocialLink(Request $request, $id = 0) {
        $request->validate([
            'social_icon' => 'required',
            'url'         => 'required',
            'followers'   => 'required|string|max:40',
        ]);

        $influencerId = authInfluencerId();

        if ($id) {
            $social       = SocialLink::where('influencer_id', $influencerId)->findOrFail($id);
            $notification = 'Social link updated successfully';
        } else {
            $test=SocialLink::where('social_icon',$request->social_icon)
                            ->where('influencer_id', $influencerId)
                            ->count();
                if($test > 0){
                    $notification = 'You cannot add this social ';
                    $notify[] = ['error', $notification];
        return back()->withNotify($notify);
                }
            $social                = new SocialLink();
            $social->influencer_id = $influencerId;
            $notification          = 'Social link added successfully';
        }

        $social->social_icon = $request->social_icon;
        $social->url         = $request->url;
        $social->followers   = $request->followers;
        $social->save();

        $notify[] = ['success', $notification];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function removeSocialLink($id) {
        $influencerId = authInfluencerId();
        SocialLink::where('influencer_id', $influencerId)->findOrFail($id)->delete();
        $notify[] = ['success', 'Social link removed successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function changePassword() {
        $pageTitle = 'Change Password';
        $influencer   = Influencer::where('id', authInfluencerId())->with('education', 'qualification', 'socialLink', 'categories')->firstOrFail();
        return view('templates.basic.influencer.profile.password', compact('pageTitle', 'influencer'));
    }

    public function addEducation(Request $request, $id = 0) {
        $request->validate([
            'country'    => 'required|string',
            'institute'  => 'required|string',
            'degree'     => 'required|string',
            'start_year' => 'required|date_format:Y',
            'end_year'   => 'required|date_format:Y|after_or_equal:start_year',
        ]);

        $influencerId = authInfluencerId();

        if ($id) {
            $education    = InfluencerEducation::where('influencer_id', $influencerId)->findOrFail($id);
            $notification = 'Education updated successfully';
        } else {
            $education                = new InfluencerEducation();
            $education->influencer_id = $influencerId;
            $notification             = 'Education added successfully';
        }

        $education->country    = $request->country;
        $education->institute  = $request->institute;
        $education->degree     = $request->degree;
        $education->start_year = $request->start_year;
        $education->end_year   = $request->end_year;
        $education->save();

        $notify[] = ['success', $notification];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function removeEducation($id) {
        $influencerId = authInfluencerId();
        InfluencerEducation::where('influencer_id', $influencerId)->where('id', $id)->delete();
        $notify[] = ['success', 'Education remove successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function addQualification(Request $request, $id = 0) {
        $request->validate([
            'certificate'  => 'required|string',
            'organization' => 'required|string',
            'summary'      => 'nullable|string',
            'year'         => 'required|date_format:Y',
        ]);

        $influencerId = authInfluencerId();

        if ($id) {
            $education    = InfluencerQualification::where('influencer_id', $influencerId)->findOrFail($id);
            $notification = 'Qualification updated successfully';
        } else {
            $education                = new InfluencerQualification();
            $education->influencer_id = $influencerId;
            $notification             = 'Qualification added successfully';
        }

        $education->certificate  = $request->certificate;
        $education->organization = $request->organization;
        $education->summary      = $request->summary;
        $education->year         = $request->year;
        $education->save();

        $notify[] = ['success', $notification];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function removeQualification($id) {
        $influencerId = authInfluencerId();
        InfluencerQualification::where('influencer_id', $influencerId)->where('id', $id)->delete();
        $notify[] = ['success', 'Qualification remove successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function submitPassword(Request $request) {

        $passwordValidation = Password::min(6);
        $general            = gs();

        if ($general->secure_password) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation],
        ]);

        $user = authInfluencer();

        if (Hash::check($request->current_password, $user->password)) {
            $password       = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = ['success', 'Password changes successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'The password doesn\'t match!'];
            return back()->withNotify($notify);
        }

    }

    public function update_birth_day(Request $r)
    {
        $user = authInfluencer();

        $user->birth_day=$r->birthday;
        $user->save();

        $notify[] = ['success', 'Birth Day changes successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }


    public function update_stat(Request $r)
    {
        // dd($r);
        $user = authInfluencer();
        $username=$user->name;
        if ($r->hasFile('stat')) {
        $files = $r->file('stat');
        // dd($files);
        $stat_array=[];
            foreach($files as $file){
                    // $filename = $file->getClientOriginalName();

                    $fichier = time().'_'.$file->getClientOriginalName();
                    $stat_array[]='influencers/stat/'.$user->id."/".$fichier;

                    $file->move(public_path('influencers/stat/'.$user->id), $fichier);


            }
            // return $stat_array;
        // dd(1);
        $user->stat=json_encode($stat_array);
        $user->save();
        }
        $notify[] = ['success', 'Statistics changed successfully'];
        return $this->returnToProfile();
        return back()->withNotify($notify);
    }

    public function submitSocial(Request $request)
    {
        $request->validate([
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'tiktok' => 'nullable|url',
            'facebook' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
            'instagram_followers' => 'nullable|numeric',
            'youtube_subscribers' => 'nullable|numeric',
            'tiktok_followers' => 'nullable|numeric',
            'facebook_followers' => 'nullable|numeric',
        ]);

        $influencer = authInfluencer();

        $influencer->social_media = [
            'instagram' => $request->instagram,
            'youtube' => $request->youtube,
            'tiktok' => $request->tiktok,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedin' => $request->linkedin,
        ];

        $influencer->social_stats = [
            'instagram_followers' => $request->instagram_followers,
            'youtube_subscribers' => $request->youtube_subscribers,
            'tiktok_followers' => $request->tiktok_followers,
            'facebook_followers' => $request->facebook_followers,
        ];

        $influencer->save();

        $notify[] = ['success', 'Social media information updated successfully'];
        return back()->withNotify($notify);
    }

    public function submitEducation(Request $request)
    {
        $request->validate([
            'degree' => 'required|string',
            'institution' => 'required|string',
            'start_year' => 'required|numeric',
            'end_year' => 'nullable|numeric',
            'description' => 'nullable|string',
            'education_index' => 'nullable|numeric',
        ]);

        $influencer = authInfluencer();
        $education = $influencer->education ?? [];

        $newEducation = [
            'degree' => $request->degree,
            'institution' => $request->institution,
            'start_year' => $request->start_year,
            'end_year' => $request->end_year,
            'description' => $request->description,
        ];

        if ($request->education_index !== null && isset($education[$request->education_index])) {
            $education[$request->education_index] = $newEducation;
            $message = 'Education updated successfully';
        } else {
            $education[] = $newEducation;
            $message = 'Education added successfully';
        }

        $influencer->education = $education;
        $influencer->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function submitSkills(Request $request)
    {
        $request->validate([
            'category' => 'required|in:technical,creative,marketing,languages',
            'name' => 'required|string',
            'level' => 'required|numeric|min:1|max:5',
        ]);

        $influencer = authInfluencer();
        $skills = $influencer->skills ?? [];

        if (!isset($skills[$request->category])) {
            $skills[$request->category] = [];
        }

        $skills[$request->category][] = [
            'name' => $request->name,
            'level' => $request->level,
        ];

        $influencer->skills = $skills;
        $influencer->save();

        $notify[] = ['success', 'Skill added successfully'];
        return back()->withNotify($notify);
    }

    public function removeSkill(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'skill_index' => 'required|numeric',
        ]);

        $influencer = authInfluencer();
        $skills = $influencer->skills ?? [];

        if (isset($skills[$request->category][$request->skill_index])) {
            unset($skills[$request->category][$request->skill_index]);
            $skills[$request->category] = array_values($skills[$request->category]);
        }

        $influencer->skills = $skills;
        $influencer->save();

        $notify[] = ['success', 'Skill removed successfully'];
        return back()->withNotify($notify);
    }

}

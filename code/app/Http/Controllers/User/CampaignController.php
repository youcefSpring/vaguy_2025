<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Campaingn;
use App\Models\Wilaya;
use App\Models\Category;
use App\Models\CampainInfluencerOffer;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\CampainOfferNotification;
use App\Models\Influencer;
use App\Models\Fichier;
use Carbon\Carbon;
use App\Models\User;
use App\Services\EmailService;
use ZipArchive;
use File;
class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {    $pageTitle="Campaign List";
        $campains=Campaingn::where('user_id',auth()->id())
                           ->with('campain_offers')
                           ->with('campain_offers.influencer')
                           ->with('category')
                           ->latest()
                           ->paginate(getPaginate(10));
          // return $campains;
       // campains = collection of Campaign
       // and each camapain contain collection of CampaignInfluencerOffer
        return view('templates.basic.user.campaigns.list',compact('pageTitle','campains'));
    }

    public function add_campaign(Request $request)
    {
        $pageTitle="Add Campaign";
        $wilayas=Wilaya::get(['id','code','name']);
        $categories = Category::all();

        // Check if there's a pre-selected influencer from URL parameters
        $preselectedInfluencer = null;
        if ($request->has('influencer_id') && $request->has('influencer_name')) {
            $influencer = Influencer::find($request->influencer_id);
            if ($influencer) {
                $preselectedInfluencer = [
                    'id' => $influencer->id,
                    'name' => $influencer->fullname,
                    'username' => $influencer->username,
                    'image' => $influencer->image
                ];
            }
        }

        return view('templates.basic.user.campaigns.add', compact('pageTitle', 'wilayas', 'categories', 'preselectedInfluencer'));
    }

    public function download_influencer_file($offer_id){
        // $influencer=Influencer::find($id);
        $offer=CampainInfluencerOffer::find($offer_id);
        $files=Fichier::where('model','CampainInfluencerOffer')
                          ->where('model_id',$offer_id)
                          ->pluck('path')->toArray();
         try{

		  $zip = new ZipArchive;
        $fileName = 'offer'.time().'.zip';
        $zipFilePath = public_path($fileName);

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            // Get the path to the directory containing the files
            $directory = public_path('campain_offers/'.$offer->campain_id.'/'.$offer_id);

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

    }
	public function count_offer_files($offer_id){
        $sum=Fichier::where('model','CampainInfluencerOffer')->where('model_id',$offer_id)->count();
        return $sum;
    }

    public function add_reason(Request $r,$id){
        $r=CampainInfluencerOffer::find($id);
        $r->reason=$r->reason;
        $r->status=$r->status;
        $r->save();
        return back();
        }

    public function job_done($offer_id){
        try{
        $a=CampainInfluencerOffer::where('id',$offer_id)->first();
        $camapin=Campaingn::where('id',$a->campain_id)->first();
        $inf=Influencer::findOrFail($a->influencer_id);
        $a->status=5;
        $a->save();

        // $inf= authInfluencer();
        $inf->balance+=$a->price;
        $inf->save();
        $transaction               = new Transaction();
        $transaction->influencer_id      = $a->influencer_id;
        $transaction->amount       = $a->price;
        $transaction->post_balance = $inf->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'campain done ';
        //$transaction->trx          = 0;
        $transaction->trx          = getTrx();
        $transaction->remark       = 'campain done ';
        $transaction->save();
        $user=User::findOrFail($camapin->user_id);
      $user->balance-=$a->price;
      $user->save();
      $transaction_2               = new Transaction();
      $transaction_2->user_id      = $camapin->user_id;
      $transaction_2->amount       = $a->price;
    //   $transaction->post_balance = $inf->balance;
      $transaction_2->post_balance = $user->balance;
      $transaction_2->trx_type     = '-';
      $transaction_2->details      = 'campain done ';
      $transaction_2->trx          = getTrx();
      $transaction_2->remark       = 'campain done ';
      $transaction_2->save();

        $new_notification=new CampainOfferNotification();
        $new_notification->campain_offer_id=$offer_id;
        $new_notification->influencer_id=$a->influencer_id;
        $new_notification->user_id=$camapin->user_id;
        $new_notification->title="Le client a validé votre travail";
        $new_notification->save();
        $details=[
            'title' =>'Campain Offer Notifications',
             'body' =>$new_notification->title,
             'subject' =>'Campain Offer Notifications #'.$a->id
      ];
      $destinations=[$inf->email];
      (new EmailService())->sendEmail($details, $destinations);
      return 1;
        }
        catch(\Exception $e){
            return 0;
        }
        finally{
            return 0;
        }

        // $this->show_detail($a->campain_id);
    }
    public function change_offer_status(Request $r,$id,$status){
        $a=CampainInfluencerOffer::where('id',$id)->first();
        $inf=Influencer::find($a->influencer_id);
        $camapin=Campaingn::where('id',$a->campain_id)->first();
        // dd($a);
        $a->status=$status;
         if(isset($r->reason)){
            $a->reason=$r->reason;
         }
        $a->save();
        // $this->campain_offers=CampainInfluencerOffer::whereCampainId($a->campain_id)->get();
        // $this->cout_totale=CampainInfluencerOffer::whereIn('status',[1,3,4,5])->whereCampainId($a->campain_id)->sum('price');
          if($status == 5){
            $payment_transaction= $this->job_done($id);
          }
        $cn=new CampainOfferNotification();
        $cn->campain_offer_id=$id;
        $cn->influencer_id=$a->influencer_id;
        $cn->user_id=$camapin->user_id;
        $cn->title="Le client a ".status_to_letters($status)." votre offre";
        $cn->save();
        $details=[
              'title' =>'Campain Offer Notifications',
               'body' =>"Le client a ".status_to_letters($status)." votre offre",
               'subject' =>'Campain Offer Notifications #'.$a->id
        ];
        $destinations=[$inf->email];
        (new EmailService())->sendEmail($details, $destinations);
        return redirect('/client/campaign');
    }

    public function show_detail($locale, $id){
        // $campain_offers=CampainInfluencerOffer::where('campain_id',$id)->get();
        $cout_totale=CampainInfluencerOffer::whereIn('status',[1,3,4,5])->where('campain_id',$id)->sum('price');
        $campain= Campaingn::with('campain_offers')->findOrFail($id);
        $pageTitle = 'Campaign Detail';
        return view('templates.basic.user.campaigns.detail', compact('pageTitle', 'campain', 'cout_totale'));
        // campain = object of Campaign
       //  contain array of CampaignInfluencerOffer[]
    }


    public function delete_campain($locale, $id){
      // NOTE: url /client/compain
      // TODO:  QLSJQKDSDL<

      // FIXME: SDKJLSDPSM
        CampainInfluencerOffer::where('campain_id',$id)->delete();
        Campaingn::find($id)->delete();
        // $this->successMessage = 'Camapin deleted Successfully.';
         return back();
    }



public function submitForm(Request $request)
{
        if(isset($request->campain_id))
                 {
                    $c=Campaingn::find($request->campain_id);
                 }
                 else{
                    $c= new Campaingn();
                    $c->user_id= auth()->id();
                 }
        //save first step
        if($request->hasFile('company_logo') && !(str_contains($request->company_logo,"files/"))){
          $originalName = $request->company_logo->getClientOriginalName();
          $request->company_logo->move('files',$originalName);
            $c->company_logo='files/'.$originalName;
        }

        $c->company_name=$request->company_name ?? $c->company_name;
        $c->company_desc=$request->company_desc ?? $c->company_desc;
        if($request->hasFile('company_principal_image') && !(str_contains($request->company_principal_image,"files/"))){
            // $c->company_principal_image=$request->company_principal_image->store('files','real_public');
            $originalName = $request->company_principal_image->getClientOriginalName();
          $request->company_principal_image->move('files',$originalName);
            $c->company_principal_image='files/'.$originalName;
        }
        // $c->company_principal_image=$this->company_principal_image->store('files','real_public');
        $c->company_principal_category=$request->company_principal_category ?? $c->company_principal_category;
        $c->company_web_url=$request->company_web_url ?? $c->company_web_url;
        //save second step
        $c->campain_name=$request->campain_name ?? $c->campain_name;
        $c->campain_objective=$request->campain_objective ?? $c->campain_objective;
        $c->campain_details=$request->campain_details ?? $c->campain_details;
        $c->campain_want=$request->campain_want ?? $c->campain_want;
        $c->campain_social_media_content=$request->campain_social_media_content ?? $c->campain_social_media_content;
        $c->campain_social_media=json_encode($request->campain_social_media) ?? $c->campain_social_media;
        $c->campain_publishing_requirement=json_encode([$request->do_this,$request->dont_do_this]);
        // $c->campain_publishing_requirement=$this->campain_publishing_requirement;
        $c->principal_category=$request->principal_category ?? $c->principal_category;
        $c->campain_start_date=$request->campain_date['from'] ?? $c->campain_start_date;
        $c->campain_end_date=$request->campain_date['to'] ?? $c->campain_end_date;

    if ($request->has('campain_photos_required')) {
        $pr = [];

        foreach ($request->campain_photos_required as $file) {
            // Check if it's a valid file instance and has valid content
            if ($file instanceof \Illuminate\Http\UploadedFile && $file->isValid()) {
                $originalName = $file->getClientOriginalName();

                // Optional: Sanitize filename
                $cleanName = preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $originalName);

                // Move to destination with cleaned name
                $file->move(public_path('files'), $cleanName);

                $pr[] = 'files/'.$cleanName;
            } else {
                // Handle invalid files
                \Log::warning('Invalid file detected', ['file' => $file]);
                continue; // Skip this entry
            }
        }

        $c->campain_photos_required = json_encode($pr);
    }


        //save third step
        $c->influencer_age_range=json_encode([$request->influencer_age_range_start,$request->influencer_age_range_end]);;
        $c->influencer_age_category=$request->influencer_age_category ?? $c->influencer_age_category;
        $c->influencer_category=$request->influencer_category ?? $c->influencer_category;
        $c->influencer_gender=$request->influencer_gender ?? $c->influencer_gender;
        $c->influencer_age=$request->influencer_age ?? $c->influencer_age;
        $c->influencer_wilaya=json_encode($request->influencer_wilaya);
        $c->influencer_interest=$request->influencer_interest ?? $c->influencer_interest;
        $c->influencer_public_age=$request->influencer_public_age ?? $c->influencer_public_age;
        $c->influencer_public_gender=$request->influencer_public_gender ?? $c->influencer_public;
        $c->influencer_public_wilaya=json_encode($request->influencer_public_wilaya);
        $c->influencer_interests=$request->influencer_interests ?? $c->influencer_interests;

        //step 5.1
        //$campany_name_2
        $c->payment_method=$request->payment_method;
        $c->coupon=$request->coupon ?? null;
        $c->date_receipt_offers_start=$request->date_receipt_offers_start ?? $c->date_receipt_offers_start;
        $c->date_receipt_offers_end=$request->date_receipt_offers_end ?? $c->date_receipt_offers_end;
        $c->campain_proposed_budget=$request->campain_proposed_budget ?? $c->campain_proposed_budget;
        $c->campain_director_name=$request->campain_director_name ?? $c->campain_director_name;
        $c->campain_director_email=$request->campain_director_email ?? $c->campain_director_email;
        $c->campain_director_phone=$request->campain_director_phone ?? $c->campain_director_phone;
        $c->campany_name=$request->company_name ?? $c->campany_name;
        $c->campany_tax_number=$request->company_tax_number ?? $c->campany_tax_number;
        $c->campany_commercial_register=$request->company_commercial_register ?? $c->campany_commercial_register;
        $c->campany_financial_officer_email=$request->company_financial_officer_email ?? $c->campany_financial_officer_email;
        $c->campany_financial_officer_phone=$request->company_financial_officer_phone   ?? $c->campany_financial_officer_phone;


       $c->campany_street=$request->company_street ?? $c->campany_street;
       $c->campany_city=$request->company_city ?? $c->campany_city;
       $c->campany_zone=$request->company_zone ?? $c->campany_zone;
       $c->campany_code_postal=$request->company_code_postal   ?? $c->campany_code_postal;
       $c->campany_country=$request->company_country ?? $c->campany_country;

       try{
        $c->save();
        // dd($c);
        $details=[
            'title' =>'New Campain in Our Platform',
             'body' =>" We are thrilled to announce the launch of a new campaign on our platform! This initiative is designed to bring you even more value and enhance your experience with us",
             'subject' =>"Vaguy- Exciting New Campaign Launch on Our Platform!"
      ];
      $destinations=Influencer::active()->pluck('email')->toArray();
      dispatch(new SendEmailJob($details, $destinations));
    //   (new EmailService())->sendEmail($details, $destinations);
        return redirect()->route('user_campaign');

       }catch (\Exception $e){
        return back();
       }



}
    public function edit($id)
    {
        $campain= Campaingn::find($id);
        $categories = Category::all();
        $wilayas=Wilaya::get(['id','code','name']);

        $pageTitle = 'Edit Campaign';
        return view('templates.basic.user.campaigns.edit', compact('pageTitle', 'campain', 'wilayas', 'categories'));

    }

    /**
     * Store method for resource controller (alias for submitForm)
     */
    public function store(Request $request)
    {
        return $this->submitForm($request);
    }

    /**
     * Update method for resource controller
     */
    public function update(Request $request, $id)
    {
        $request->merge(['campain_id' => $id]);
        return $this->submitForm($request);
    }

    /**
     * Show method for resource controller (alias for show_detail)
     */
    public function show($id)
    {
        return $this->show_detail($id);
    }

    /**
     * Create method for resource controller (alias for add_campaign)
     */
    public function create(Request $request)
    {
        return $this->add_campaign($request);
    }

    /**
     * Destroy method for resource controller (alias for delete_campain)
     */
    public function destroy($id)
    {
        return $this->delete_campain($id);
    }

    /**
     * Store campaign via Vue.js (API endpoint)
     */
    public function storeVue(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'company_name' => 'required|string|max:255',
                'company_desc' => 'required|string',
                'company_principal_category' => 'required|exists:categories,id',
                'company_web_url' => 'nullable|url',
                'campain_name' => 'required|string|max:255',
                'campain_objective' => 'required|string|max:255',
                'campain_details' => 'required|string',
                'campain_start_date' => 'required|date|after_or_equal:today',
                'campain_end_date' => 'required|date|after_or_equal:campain_start_date',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'company_principal_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                '*.required' => 'هذا الحقل مطلوب',
                'company_web_url.url' => 'يجب أن يكون رابط صحيح',
                'campain_start_date.after_or_equal' => 'تاريخ البداية يجب أن يكون اليوم أو بعده',
                'campain_end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
                'company_logo.image' => 'يجب أن يكون الشعار صورة',
                'company_principal_image.image' => 'يجب أن تكون الصورة الرئيسية صورة',
            ]);

            $campaign = new Campaingn();
            $campaign->user_id = auth()->id();

            // Handle file uploads
            if ($request->hasFile('company_logo')) {
                $campaign->company_logo = $request->file('company_logo')->store('files', 'real_public');
            }

            if ($request->hasFile('company_principal_image')) {
                $campaign->company_principal_image = $request->file('company_principal_image')->store('files', 'real_public');
            }

            // Fill basic data
            $campaign->company_name = $validatedData['company_name'];
            $campaign->company_desc = $validatedData['company_desc'];
            $campaign->company_principal_category = $validatedData['company_principal_category'];
            $campaign->company_web_url = $validatedData['company_web_url'];
            $campaign->campain_name = $validatedData['campain_name'];
            $campaign->campain_objective = $validatedData['campain_objective'];
            $campaign->campain_details = $validatedData['campain_details'];
            $campaign->campain_start_date = $validatedData['campain_start_date'];
            $campaign->campain_end_date = $validatedData['campain_end_date'];

            // Set default values for optional fields
            $campaign->campain_want = $request->input('campain_want', '');
            $campaign->campain_social_media = $request->input('campain_social_media', '');
            $campaign->campain_social_media_content = $request->input('campain_social_media_content', '');
            $campaign->campain_publishing_requirement = json_encode(['', '']);
            $campaign->campain_photos_required = json_encode([]);
            $campaign->influencer_age_range = json_encode([18, 35]);
            $campaign->influencer_wilaya = json_encode([]);
            $campaign->influencer_public_wilaya = json_encode([]);

            // Set dates for offer reception (default to campaign dates)
            $campaign->date_receipt_offers_start = $validatedData['campain_start_date'];
            $campaign->date_receipt_offers_end = $validatedData['campain_end_date'];

            $campaign->save();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحملة بنجاح',
                'campaign' => $campaign
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Vue Campaign creation error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحملة'
            ], 500);
        }
    }

    /**
     * Get campaigns data for Vue.js (API endpoint)
     */
    public function getCampaignsData()
    {
        try {
            $campaigns = Campaingn::where('user_id', auth()->id())
                ->with('campain_offers')
                ->with('category')
                ->latest()
                ->get();

            return response()->json($campaigns);
        } catch (\Exception $e) {
            \Log::error('Error loading campaigns data: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'خطأ في تحميل البيانات'
            ], 500);
        }
    }

}

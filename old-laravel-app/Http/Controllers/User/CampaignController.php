<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCampaignRequest;
use App\Http\Requests\UpdateCampaignRequest;
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
    {
        $pageTitle = "My Campaigns";
        $pageDescription = "Manage and track all your marketing campaigns";
        $pageIcon = "bi bi-megaphone";
        $breadcrumbs = [
            ['title' => 'Marketing', 'url' => '#'],
            ['title' => 'Campaigns', 'url' => route('user_campaign')]
        ];
        $pageActions = '<a href="' . route('add_campaign') . '" class="btn btn-primary">
                           <i class="bi bi-plus-circle me-1"></i>' . __('Create Campaign') . '
                       </a>';

        $campains = Campaingn::where('user_id', auth()->id())
                           ->with('campain_offers')
                           ->with('campain_offers.influencer')
                           ->with('category')
                           ->latest()
                           ->paginate(getPaginate(10));

        return view('templates.basic.user.campaigns.list', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'pageActions',
            'campains'
        ));
    }

    public function add_campaign()
    {
        $pageTitle = "Create New Campaign";
        $pageDescription = "Launch a new marketing campaign to reach your target audience";
        $pageIcon = "bi bi-plus-circle";
        $breadcrumbs = [
            ['title' => 'Marketing', 'url' => '#'],
            ['title' => 'Campaigns', 'url' => route('user_campaign')],
            ['title' => 'Create Campaign', 'url' => route('add_campaign')]
        ];

        $wilayas = Wilaya::get(['id', 'code', 'name']);
        $categories = Category::all();

        return view('templates.basic.user.campaigns.add', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'wilayas',
            'categories'
        ));
    }

    public function store(StoreCampaignRequest $request)
    {
        try {
            $campaign = new Campaingn();
            $campaign->user_id = auth()->id();
            $campaign->status = $request->status ?? 'pending';

            // Handle file uploads with better error handling
            if ($request->hasFile('company_logo')) {
                $logoPath = $this->handleFileUpload($request->file('company_logo'), 'campaigns/logos');
                if ($logoPath) {
                    $campaign->company_logo = $logoPath;
                }
            }

            if ($request->hasFile('company_principal_image')) {
                $imagePath = $this->handleFileUpload($request->file('company_principal_image'), 'campaigns/images');
                if ($imagePath) {
                    $campaign->company_principal_image = $imagePath;
                }
            }

            // Handle multiple photo uploads
            if ($request->hasFile('campain_photos_required')) {
                $photoPaths = [];
                foreach ($request->file('campain_photos_required') as $photo) {
                    $photoPath = $this->handleFileUpload($photo, 'campaigns/photos');
                    if ($photoPath) {
                        $photoPaths[] = $photoPath;
                    }
                }
                $campaign->campain_photos_required = json_encode($photoPaths);
            }

            // Fill validated data
            $fillableData = $request->validated();

            // Process special fields
            if (isset($fillableData['campain_social_media'])) {
                $fillableData['campain_social_media'] = json_encode($fillableData['campain_social_media']);
            }

            if (isset($fillableData['do_this']) || isset($fillableData['dont_do_this'])) {
                $publishingRequirement = [
                    'do_this' => $fillableData['do_this'] ?? '',
                    'dont_do_this' => $fillableData['dont_do_this'] ?? ''
                ];
                $fillableData['campain_publishing_requirement'] = json_encode($publishingRequirement);
                unset($fillableData['do_this'], $fillableData['dont_do_this']);
            }

            // Handle influencer targeting
            if (isset($fillableData['influencer_age_range_start']) && isset($fillableData['influencer_age_range_end'])) {
                $fillableData['influencer_age_range'] = json_encode([
                    $fillableData['influencer_age_range_start'],
                    $fillableData['influencer_age_range_end']
                ]);
                unset($fillableData['influencer_age_range_start'], $fillableData['influencer_age_range_end']);
            }

            if (isset($fillableData['influencer_wilaya'])) {
                $fillableData['influencer_wilaya'] = json_encode($fillableData['influencer_wilaya']);
            }

            // Remove file upload fields from fillable data as they're handled separately
            unset($fillableData['company_logo'], $fillableData['company_principal_image'], $fillableData['campain_photos_required']);

            // Fill campaign with validated data
            foreach ($fillableData as $key => $value) {
                $campaign->$key = $value;
            }

            $campaign->save();

            // Send notification email to influencers
            $this->notifyInfluencersNewCampaign($campaign);

            $notify[] = ['success', __('Campaign created successfully!')];
            return redirect()->route('user_campaign')->withNotify($notify);

        } catch (\Exception $e) {
            \Log::error('Campaign creation failed: ' . $e->getMessage());
            $notify[] = ['error', __('Failed to create campaign. Please try again.')];
            return back()->withNotify($notify)->withInput();
        }
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

    public function show_detail($id){
        // $campain_offers=CampainInfluencerOffer::where('campain_id',$id)->get();
        $cout_totale=CampainInfluencerOffer::whereIn('status',[1,3,4,5])->where('campain_id',$id)->sum('price');
        $campain= Campaingn::with('campain_offers')->findOrFail($id);
        $pageTitle = 'Campaign Detail';
        return view('templates.basic.user.campaigns.detail', compact('pageTitle', 'campain', 'cout_totale'));
    }


    public function delete_campain($id){
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
        if(!(str_contains($request->company_logo,"files/"))){
          $originalName = $request->company_logo->getClientOriginalName();
          $request->company_logo->move('files',$originalName);
            $c->company_logo='files/'.$originalName;
        }

        $c->company_name=$request->company_name ?? $c->company_name;
        $c->company_desc=$request->company_desc ?? $c->company_desc;
        if(!(str_contains($request->company_principal_image,"files/"))){
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
        return redirect(route('user_campaign'));
        return redirect()->route('user_campaign');

       }catch (\Exception $e){
        return back();
       }
    }
    public function edit($id)
    {
        $campain = Campaingn::find($id);

        // Check if user owns this campaign
        if (!$campain || $campain->user_id !== auth()->id()) {
            $notify[] = ['error', __('Campaign not found or unauthorized access.')];
            return redirect()->route('user_campaign')->withNotify($notify);
        }

        $categories = Category::all();
        $wilayas = Wilaya::get(['id','code','name']);

        $pageTitle = 'Edit Campaign';
        return view('templates.basic.user.campaigns.edit', compact('pageTitle', 'campain', 'wilayas', 'categories'));
    }

    public function update(UpdateCampaignRequest $request, $id)
    {
        try {
            $campaign = Campaingn::findOrFail($id);

            // Ensure user owns this campaign
            if ($campaign->user_id !== auth()->id()) {
                $notify[] = ['error', __('Unauthorized access.')];
                return redirect()->route('user_campaign')->withNotify($notify);
            }

            // Handle file uploads
            if ($request->hasFile('company_logo')) {
                $logoPath = $this->handleFileUpload($request->file('company_logo'), 'campaigns/logos');
                if ($logoPath) {
                    // Delete old logo if exists
                    if ($campaign->company_logo) {
                        $this->deleteFile($campaign->company_logo);
                    }
                    $campaign->company_logo = $logoPath;
                }
            }

            if ($request->hasFile('company_principal_image')) {
                $imagePath = $this->handleFileUpload($request->file('company_principal_image'), 'campaigns/images');
                if ($imagePath) {
                    // Delete old image if exists
                    if ($campaign->company_principal_image) {
                        $this->deleteFile($campaign->company_principal_image);
                    }
                    $campaign->company_principal_image = $imagePath;
                }
            }

            // Handle multiple photo uploads
            if ($request->hasFile('campain_photos_required')) {
                $photoPaths = [];
                foreach ($request->file('campain_photos_required') as $photo) {
                    $photoPath = $this->handleFileUpload($photo, 'campaigns/photos');
                    if ($photoPath) {
                        $photoPaths[] = $photoPath;
                    }
                }

                // Delete old photos if exists
                if ($campaign->campain_photos_required) {
                    $oldPhotos = json_decode($campaign->campain_photos_required, true);
                    if (is_array($oldPhotos)) {
                        foreach ($oldPhotos as $oldPhoto) {
                            $this->deleteFile($oldPhoto);
                        }
                    }
                }

                $campaign->campain_photos_required = json_encode($photoPaths);
            }

            // Fill validated data
            $fillableData = $request->validated();

            // Process special fields (same as store method)
            if (isset($fillableData['campain_social_media'])) {
                $fillableData['campain_social_media'] = json_encode($fillableData['campain_social_media']);
            }

            if (isset($fillableData['do_this']) || isset($fillableData['dont_do_this'])) {
                $publishingRequirement = [
                    'do_this' => $fillableData['do_this'] ?? '',
                    'dont_do_this' => $fillableData['dont_do_this'] ?? ''
                ];
                $fillableData['campain_publishing_requirement'] = json_encode($publishingRequirement);
                unset($fillableData['do_this'], $fillableData['dont_do_this']);
            }

            if (isset($fillableData['influencer_age_range_start']) && isset($fillableData['influencer_age_range_end'])) {
                $fillableData['influencer_age_range'] = json_encode([
                    $fillableData['influencer_age_range_start'],
                    $fillableData['influencer_age_range_end']
                ]);
                unset($fillableData['influencer_age_range_start'], $fillableData['influencer_age_range_end']);
            }

            if (isset($fillableData['influencer_wilaya'])) {
                $fillableData['influencer_wilaya'] = json_encode($fillableData['influencer_wilaya']);
            }

            // Remove file upload fields from fillable data
            unset($fillableData['company_logo'], $fillableData['company_principal_image'], $fillableData['campain_photos_required']);

            // Update campaign with validated data
            foreach ($fillableData as $key => $value) {
                $campaign->$key = $value;
            }

            $campaign->save();

            $notify[] = ['success', __('Campaign updated successfully!')];
            return redirect()->route('user_campaign')->withNotify($notify);

        } catch (\Exception $e) {
            \Log::error('Campaign update failed: ' . $e->getMessage());
            $notify[] = ['error', __('Failed to update campaign. Please try again.')];
            return back()->withNotify($notify)->withInput();
        }
    }

    /**
     * Handle file upload with validation
     */
    private function handleFileUpload($file, $directory)
    {
        try {
            if ($file && $file->isValid()) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $filename, 'public');
                return $path;
            }
        } catch (\Exception $e) {
            \Log::error('File upload failed: ' . $e->getMessage());
        }
        return null;
    }

    /**
     * Delete file from storage
     */
    private function deleteFile($filePath)
    {
        try {
            if ($filePath && \Storage::disk('public')->exists($filePath)) {
                \Storage::disk('public')->delete($filePath);
            }
        } catch (\Exception $e) {
            \Log::error('File deletion failed: ' . $e->getMessage());
        }
    }

    /**
     * Send notification to influencers about new campaign
     */
    private function notifyInfluencersNewCampaign($campaign)
    {
        try {
            $details = [
                'title' => __('New Campaign Available'),
                'body' => __('A new campaign has been launched on our platform! Check it out and submit your proposal.'),
                'subject' => __('Vaguy - New Campaign: :name', ['name' => $campaign->campain_name])
            ];

            $destinations = Influencer::active()->pluck('email')->toArray();

            if (!empty($destinations)) {
                dispatch(new SendEmailJob($details, $destinations));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send campaign notification: ' . $e->getMessage());
        }
    }

}

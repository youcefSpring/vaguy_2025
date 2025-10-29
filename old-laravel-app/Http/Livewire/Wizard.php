<?php

namespace App\Http\Livewire;
use Livewire\WithFileUploads;
use Livewire\Component;
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
use ZipArchive;
use File;

class Wizard extends Component
{
    use WithFileUploads;
    public $camapin_id='';
    public $cout_totale=0;
    public $display=1, $updateMode=0, $addMode=0,$detailMode=0,$campain_offers=[];
    public $currentStep = 1;
    public $name, $amount, $description, $status = 1, $stock;
    public $successMessage = '';
    public $user_campains=[];
	public $user_balance=0;
    // public Campaingn $com;
       public $wilayas,$wilaya=[];
       public $categories=[];
       public $reason='';
       //step 1
    public $company_logo ,$company_name ,$company_desc ,
           $company_principal_image,
           $company_principal_category ,$company_web_url;
     //step 2

    public $campain_name,$campain_objective,$campain_details,$campain_want,
           $campain_photos_required=[],$campain_social_media,$campain_social_media_content,
           $campain_publishing_requirement=[],$principal_category,
           $campain_start_date,$campain_end_date,$do_this,$dont_do_this;

   public $campain_social_media_multiple=[];

    public $influencer_age_range,$influencer_age_range_start=13,$influencer_age_range_end='',$influencer_age_category,$influencer_category,
           $influencer_gender,$influencer_age,$influencer_wilaya=[],
           $influencer_interest,$influencer_public_age,$influencer_public_gender,
           $influencer_public_wilaya=[],$influencer_interests;


           //step 5
    public $payment_method,$coupon, $date_receipt_offers_start,$date_receipt_offers_end,$campain_proposed_budget,
           $campain_director_name,$campain_director_email,$campain_director_phone,
           $campany_name,$campany_name_2,$campany_tax_number,$campany_commercial_register,
           $campany_financial_officer_email,$campany_financial_officer_phone;
    public $campany_street,$campany_city,$campany_zone,$campany_code_postal,$campany_country="Algeria";



    protected $rules = [
        // 'company_logo' => 'image|max:1024',
        'company_logo' => 'image',
        'company_name' => 'required',
		'campain_start_date' => 'required|date|after_or_equal:today',
		 'campain_end_date' => 'required|date|after_or_equal:today|after_or_equal:campain_start_date',
        'company_desc' => 'required',
        'company_principal_image' => 'required',
        'company_principal_category' => 'required',
        'company_web_url' => 'string|url',
    ];
	
	 public $listeners = ['variablesSelected' => 'handleVariablesSelected'];

    public function handleVariablesSelected($variables)
    {
        $this->$campain_social_media_multiple = $variables;
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
    public function mount()
    {
      $this->user_campains=Campaingn::where('user_id',auth()->id())->get();
      // $this->user_campains=Campaingn::where('user_id','>',0)->get();
      $this->wilayas=Wilaya::get(['id','code','name']);
      $this->com = new \App\Models\Campaingn ();
      $this->categories=Category::all();
      $this->campain_social_media="facebook";
		$this->campain_social_media_multiple[0]="facebook";
      $this->campain_start_date = now()->format('Y-m-d');
      $this->campain_end_date = now()->format('Y-m-d');
      $this->user_balance=auth()->user()->balance;
      //   dd(is_array($this->campain_photos_required));
    }

    public function add_reason($id){
        $r=CampainInfluencerOffer::find($id);
        $r->reason=$this->reason;
        $r->save();
        $this->reason='';

    }
    public function render()
    {
        return view('livewire.campaign.wizard');
    }

       public function job_done($id,$influencer_id){
        $a=CampainInfluencerOffer::where('id',$id)->first();
        $camapin=Campaingn::where('id',$a->campain_id)->first();
        $inf=Influencer::find($a->influencer_id);
        $a->status=5;
        $a->save();

        // $inf= authInfluencer();
        $inf->balance+=$a->price;
        $inf->save();
        $transaction               = new Transaction();
        $transaction->influencer_id      = $influencer_id;
        $transaction->amount       = $a->price;
        $transaction->post_balance = $inf->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'campain done ';
        //$transaction->trx          = 0;
        $transaction->trx          = getTrx();
        $transaction->remark       = 'campain done ';
        $transaction->save();

        // $inf= authInfluencer();
        
		 // create transaction for client
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
      
        $cn=new CampainOfferNotification();
        $cn->campain_offer_id=$id;
        $cn->influencer_id=$a->influencer_id;
        $cn->user_id=$camapin->user_id;
        $cn->title="Le client a validé votre travail";
        $cn->save();

        $this->show_detail($a->campain_id);
    }
    public function change_offer_status($id,$influencer_id,$status){
        $a=CampainInfluencerOffer::where('id',$id)->first();
        $camapin=Campaingn::where('id',$a->campain_id)->first();
        // dd($a);
        $a->status=$status;
        $a->save();
        $this->campain_offers=CampainInfluencerOffer::whereCampainId($a->campain_id)->get();
        $this->cout_totale=CampainInfluencerOffer::whereIn('status',[1,3,4,5])->whereCampainId($a->campain_id)->sum('price');
        // $depo=Deposit::where('user_id',auth()->user()->id)->where('status',1)->sum('amount');


        // $user = auth()->user();
        // $user->balance -= $a->price;
        // $user->save();
        // if($status == 5){

        //     $transaction               = new Transaction();
        //     $transaction->user_id      = $user->id;
        //     $transaction->amount       = $a->price;
        //     $transaction->post_balance = $user->balance;
        //     $transaction->trx_type     = '-';
        //     $transaction->trx          = getTrx();
        //     $transaction->details      = 'Deducted for camapain expense';
        //     $transaction->remark       = 'camapin_payment';
        //     $transaction->save();
        // }
        $cn=new CampainOfferNotification();
        $cn->campain_offer_id=$id;
        $cn->influencer_id=$a->influencer_id;
        $cn->user_id=$camapin->user_id;
        $cn->title="Le client a ".status_to_letters($status)." votre offre";
        $cn->save();
    }


    public function show_detail($id){
        $this->campain_offers=CampainInfluencerOffer::where('campain_id',$id)->get();
        // dd($this->campain_offers);
        $this->cout_totale=CampainInfluencerOffer::whereIn('status',[1,3,4,5])->where('campain_id',$id)->sum('price');

        $this->detailMode=1;
        $this->updateMode=0;
        $this->display=0;

        $old= Campaingn::find($id);
        $this->company_logo=$old->company_logo;
        $this->company_name=$old->company_name;
        $this->company_desc=$old->company_desc;
        $this->company_principal_image=$old->company_principal_image;
        $this->company_principal_category=$old->company_principal_category;
        $this->company_web_url=$old->company_web_url;

        //save second step

        $this->campain_name=$old->campain_name;
        $this->campain_objective=$old->campain_objective;
        $this->campain_details=$old->campain_details;
        $this->campain_want=$old->campain_want;
        $this->campain_social_media_content=$old->campain_social_media_content;
        // dd($this->campain_social_media_content);
        $this->campain_social_media=$old->campain_social_media;
        $publish_require_edit=array();
        $publish_require_edit=json_decode($old->campain_publishing_requirement,false);
        // dd(($publish_require_edit[1]));
        $this->do_this= $publish_require_edit[0];
        $this->dont_do_this= $publish_require_edit[1];
        // $this->campain_publishing_requirement=$old->campain_publishing_requirement;

        $this->principal_category=$old->principal_category;
        $this->campain_start_date=$old->campain_start_date;
        $this->campain_end_date=$old->campain_end_date;

        //  $pr=[];
        // foreach($this->campain_photos_required as $a){
        //    $pr[]=$a->store('files','real_public');
        // }
        $this->campain_photos_required=json_decode($old->campain_photos_required,true);
        //    dd(str_contains($this->campain_photos_required[0],"files/"));
        //save third step
        $age_ranges=array();
        $age_ranges=json_decode($old->influencer_age_range,false);
        $this->influencer_age_range_start=$age_ranges[0];
        $this->influencer_age_range_end=$age_ranges[1];
        // $this->influencer_age_range=$old->influencer_age_range;
        $this->influencer_age_category=$old->influencer_age_category;
        $this->influencer_category=$old->influencer_category;
        $this->influencer_gender=$old->influencer_gender;
        $this->influencer_age=$old->influencer_age;
        $this->influencer_wilaya=json_encode($old->influencer_wilaya);
        $this->influencer_interest=$old->influencer_interest;
        $this->influencer_public_age=$old->influencer_public_age;
        $this->influencer_public_gender=$old->influencer_public_gender;
        $this->influencer_public_wilaya=$old->influencer_public_wilaya;
        $this->influencer_interests=$old->influencer_interests;

        //step 5.1
        //$campany_name_2
        $this->payment_method=$old->payment_method;
        $this->coupon=$old->coupon ?? null;
        $this->date_receipt_offers_start=$old->date_receipt_offers_start;
        $this->date_receipt_offers_end=$old->date_receipt_offers_end;
        $this->campain_proposed_budget=$old->campain_proposed_budget;
        $this->campain_director_name=$old->campain_director_name;
        $this->campain_director_email=$old->campain_director_email;
        $this->campain_director_phone=$old->campain_director_phone;
        $this->campany_name=$old->campany_name;
        $this->campany_tax_number=$old->campany_tax_number;
        $this->campany_commercial_register=$old->campany_commercial_register;
        $this->campany_financial_officer_email=$old->campany_financial_officer_email;
        $this->campany_financial_officer_phone=$old->campany_financial_officer_phone;

       //  $this->reset();
       //  dd(1);

       $this->campany_street=$old->campany_street;
       $this->campany_city=$old->campany_city;
       $this->campany_zone=$old->campany_zone;
       $this->campany_code_postal=$old->campany_code_postal;
       $this->campany_country=$old->campany_country;
    }

    public function add_campaign(){
        $this->display=0;
        $this->updateMode=0;
        $this->addMode=1;
        $this->detailMode=0;
    }
    public function add_campaign_2(){
        $this->display=0;
        $this->updateMode=0;
        $this->addMode=1;
        $this->detailMode=0;
    }

    public function back_to_campaign_list(){
        $this->display=1;
        $this->updateMode=0;
        $this->detailMode=0;
        $this->currentStep=1;
        $this->successMessage='';
        $this->mount();
        $this->render();
        $this->clearForm();
    }

    public function delete_campain($id){
        Campaingn::find($id)->delete();
        $this->successMessage = 'Camapin deleted Successfully.';
        $this->mount();
       $this->render();
    }


    public function firstStepSubmit()
    {

        // dd($this->company_principal_category);
        $validatedData = $this->validate([
                'company_logo' => 'required',
                'company_name' => 'required',
                'company_desc' => 'required',
                'company_principal_image' => 'required',
                'company_principal_category' => 'required',
                // 'company_web_url' => 'required',
        ], [
            '*.required' => trans('الحقل اجباري')
        ]);

        // dd($this->company_logo);
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        $validatedData = $this->validate([
            'campain_name'=>'required',
            'campain_objective'=>'required',
            'campain_details'=>'required',
            'campain_want'=>'required',
        //    'campain_photos_required'=>'required',:
           'campain_social_media'=>'required',
           'campain_social_media_content'=>'required',
        //    'campain_publishing_requirement'=>'required',
           'campain_start_date'=>'required|date|after_or_equal:today',
           'campain_end_date'=>'required|after_or_equal:campain_start_date|after_or_equal:today'
        ], [
            '*.required' => trans('الحقل اجباري'),
            'campain_start_date.after' => trans('تاريخ بدء الحملة يجب أن يكون صحيحا'),
            'campain_end_date.after' => trans('تاريخ نهاية الحملة يجب أن يكون صحيحا و بعد تاريخ بدء الحملة'),
        ]);

        $this->currentStep = 3;
    }

    public function thirdStepSubmit()
    {
        $validatedData = $this->validate([
            'influencer_age_range_end' =>'gte:influencer_age_range_start',
        //     'campain_name'=>'required',
        //     'campain_objective'=>'required',
        //     'campain_details'=>'required',
        //     'campain_want'=>'required',
        // //    'campain_photos_required'=>'required',
        // //    'campain_social_media'=>'required',
        //    'campain_social_content'=>'required',
        //    'campain_publishing_requirement'=>'required',
        //    'campain_start_date'=>'required',
        //    'campain_end_date'=>'required'
        ], [
            'campain_name.required' => 'حقل اسم الحملة مطلوب.',
            'campain_objective.required' => 'حقل هدف الحملة مطلوب.',
            'campain_details.required' => 'حقل تفاصيل الحملة مطلوب.',
            'campain_want.required' => 'حقل ما تريد الحملة مطلوب.',
            'do_this.required' => 'حقل ما ستفعله الحملة مطلوب.',
            'dont_do_this.required' => 'حقل ما لن تفعله الحملة مطلوب.',
            'campain_social_media.required' => 'حقل منصة السوشيال ميديا مطلوب.',
            'campain_social_media_content.required' => 'حقل محتوى السوشيال ميديا مطلوب.',
            'campain_start_date.required' => 'حقل تاريخ بدء الحملة مطلوب.',
            'campain_start_date.date' => 'تاريخ بدء الحملة يجب أن يكون تاريخ صحيح.',
            'campain_start_date.after' => 'تاريخ بدء الحملة يجب أن يكون بعد اليوم.',
            'campain_end_date.required' => 'حقل تاريخ نهاية الحملة مطلوب.',
            'campain_end_date.after' => 'تاريخ نهاية الحملة يجب أن يكون بعد تاريخ بدء الحملة واليوم.',
        ]);
        if(isset($company_principal_category)){
              $this->principal_category=\App\Models\Category::find($company_principal_category)->name ?? $this->company_principal_category;
        }
        // dd($this->influencer_public_wilaya);
        $this->currentStep = 4;
    }


     public function validationStepSubmit(){
        $this->currentStep = 5;
     }

    public function back($step)
    {

        // dd( $this->currentStep);
        $this->currentStep = $step;
    }

    public function step_6()
    {

        $this->currentStep = 6;
    }

    public function clearForm()
    {
       $this->company_logo='';
       $this->company_name='';
       $this->company_desc='';
       $this->company_principal_image='';
       $this->company_principal_category='';
       $this->company_web_url='';

        //save second step

        $this->campain_name='';
        $this->campain_objective='';
        $this->campain_details='';
        $this->campain_want='';
        $this->campain_social_content='';
        $this->campain_social_media='';
        $this->campain_publishing_requirement='';
        $this->principal_category='';
        $this->campain_start_date='';
        $this->campain_end_date='';

        $this->campain_photos_required=[];

        //save third step
        $this->influencer_age_range='';
        $this->influencer_age_category='';
        $this->influencer_category='';
        $this->influencer_gender='';
        $this->influencer_age='';
        $this->influencer_wilaya=[];
        $this->influencer_interest='';
        $this->influencer_public_age='';
        $this->influencer_public_gender='';
        $this->influencer_public_wilaya='';
        $this->influencer_interests='';

        //step 5.1
        //$campany_name_2
        $this->payment_method='';
        $this->coupon='';
        $this->date_receipt_offers_start='';
        $this->date_receipt_offers_end='';
        $this->campain_proposed_budget='';
        $this->campain_director_name='';
        $this->campain_director_email='';
        $this->campain_director_phone='';
        $this->campany_name='';
        $this->campany_tax_number='';
        $this->campany_commercial_register='';
        $this->campany_financial_officer_email='';
        $this->campany_financial_officer_phone='';

       $this->campany_street='';
       $this->campany_city='';
       $this->campany_zone='';
       $this->campany_code_postal='';
       $this->campany_country='';
    //    $this->successMessage='';
    }

public function submitForm()
{
        $validatedData = $this->validate([
            'date_receipt_offers_start' => 'required|date',
            'date_receipt_offers_end' => 'required|after:date_receipt_offers_start',
            // 'payment_method'=>'required'
        ]);
        if(isset($this->campain_id))
                 {
                    $c=Campaingn::find($this->campain_id);
                    //  $this->successMessage='Campain Updated Successfully';
                 }
                 else{
                    $c= new Campaingn();
                    $c->user_id= auth()->id();
                    // $this->successMessage='Campain Saved Successfully';
                 }
        //save first step
        if(!(str_contains($this->company_logo,"files/"))){
            $c->company_logo=$this->company_logo->store('files','real_public');
        }

        $c->company_name=$this->company_name;
        $c->company_desc=$this->company_desc;
        if(!(str_contains($this->company_principal_image,"files/"))){
            $c->company_principal_image=$this->company_principal_image->store('files','real_public');
        }
        // $c->company_principal_image=$this->company_principal_image->store('files','real_public');
        $c->company_principal_category=$this->company_principal_category;
        $c->company_web_url=$this->company_web_url;

        //save second step

        $c->campain_name=$this->campain_name;
        $c->campain_objective=$this->campain_objective;
        $c->campain_details=$this->campain_details;
        $c->campain_want=$this->campain_want;
        $c->campain_social_media_content=$this->campain_social_media_content;
        $c->campain_social_media=$this->campain_social_media;
        $c->campain_publishing_requirement=json_encode([$this->do_this,$this->dont_do_this]);
        // $c->campain_publishing_requirement=$this->campain_publishing_requirement;
        $c->principal_category=$this->principal_category;
        $c->campain_start_date=$this->campain_start_date;
        $c->campain_end_date=$this->campain_end_date;

        if(!(str_contains($this->campain_photos_required[0],"files/"))){
         $pr=[];
        foreach($this->campain_photos_required as $a){
           $pr[]=$a->store('files','real_public');
        }
        $c->campain_photos_required=json_encode($pr);
        }
        //save third step
        $c->influencer_age_range=json_encode([$this->influencer_age_range_start,$this->influencer_age_range_end]);;
        $c->influencer_age_category=$this->influencer_age_category;
        $c->influencer_category=$this->influencer_category;
        $c->influencer_gender=$this->influencer_gender;
        $c->influencer_age=$this->influencer_age;
        $c->influencer_wilaya=json_encode($this->influencer_wilaya);
        $c->influencer_interest=$this->influencer_interest;
        $c->influencer_public_age=$this->influencer_public_age;
        $c->influencer_public_gender=$this->influencer_public_gender;
        $c->influencer_public_wilaya=json_encode($this->influencer_public_wilaya);
        $c->influencer_interests=$this->influencer_interests;

        //step 5.1
        //$campany_name_2
        $c->payment_method=$this->payment_method;
        $c->coupon=$this->coupon ?? null;
        $c->date_receipt_offers_start=$this->date_receipt_offers_start;
        $c->date_receipt_offers_end=$this->date_receipt_offers_end;
        $c->campain_proposed_budget=$this->campain_proposed_budget;
        $c->campain_director_name=$this->campain_director_name;
        $c->campain_director_email=$this->campain_director_email;
        $c->campain_director_phone=$this->campain_director_phone;
        $c->campany_name=$this->campany_name;
        $c->campany_tax_number=$this->campany_tax_number;
        $c->campany_commercial_register=$this->campany_commercial_register;
        $c->campany_financial_officer_email=$this->campany_financial_officer_email;
        $c->campany_financial_officer_phone=$this->campany_financial_officer_phone;

       //  $this->reset();
       //  dd(1);

       $c->campany_street=$this->campany_street;
       $c->campany_city=$this->campany_city;
       $c->campany_zone=$this->campany_zone;
       $c->campany_code_postal=$this->campany_code_postal;
       $c->campany_country=$this->campany_country;
      //    dd($c);
        $c->save();
        if(isset($this->campain_id))
                 {

                     $this->successMessage='Campain Updated Successfully';
                 }
                 else{

                    $this->successMessage='Campain Saved Successfully';
                 }
        // $this->successMessage = 'Product Created Successfully.';
        $this->clearForm();
        $this->currentStep = 6;
        // dd($this->company_logo);
}
    public function edit($id)
    {
        $this->campain_id=$id;
        $this->display=0;
         $this->updateMode=1;
         $this->addMode=0;

        $old= Campaingn::find($id);
        // dd($old);:
        // $c->user_id= auth()->id();
        //save first step
        // $c->company_logo=$this->company_logo->store('files','real_public');
        $this->company_logo=$old->company_logo;
        $this->company_name=$old->company_name;
        $this->company_desc=$old->company_desc;
        $this->company_principal_image=$old->company_principal_image;
        $this->company_principal_category=$old->company_principal_category;
        $this->company_web_url=$old->company_web_url;

        //save second step

        $this->campain_name=$old->campain_name;
        $this->campain_objective=$old->campain_objective;
        $this->campain_details=$old->campain_details;
        $this->campain_want=$old->campain_want;
        $this->campain_social_media_content=$old->campain_social_media_content;
        // dd($this->campain_social_media_content);
        $this->campain_social_media=$old->campain_social_media;
        $publish_require_edit=array();
        $publish_require_edit=json_decode($old->campain_publishing_requirement,false);
        // dd(($publish_require_edit[1]));
        $this->do_this= $publish_require_edit[0];
        $this->dont_do_this= $publish_require_edit[1];
        // $this->campain_publishing_requirement=$old->campain_publishing_requirement;

        $this->principal_category=$old->principal_category;
        $this->campain_start_date=$old->campain_start_date;
        $this->campain_end_date=$old->campain_end_date;

        //  $pr=[];
        // foreach($this->campain_photos_required as $a){
        //    $pr[]=$a->store('files','real_public');
        // }
        $this->campain_photos_required=json_decode($old->campain_photos_required,true);
        //    dd(str_contains($this->campain_photos_required[0],"files/"));
        //save third step
        $age_ranges=array();
        $age_ranges=json_decode($old->influencer_age_range,false);
        $this->influencer_age_range_start=$age_ranges[0];
        $this->influencer_age_range_end=$age_ranges[1];

        $this->influencer_age_category=$old->influencer_age_category;
        $this->influencer_category=$old->influencer_category;
        $this->influencer_gender=$old->influencer_gender;
        $this->influencer_age=$old->influencer_age;
        $this->influencer_wilaya=json_encode($old->influencer_wilaya);
        $this->influencer_interest=$old->influencer_interest;
        $this->influencer_public_age=$old->influencer_public_age;
        $this->influencer_public_gender=$old->influencer_public_gender;
        $this->influencer_public_wilaya=$old->influencer_public_wilaya;
        $this->influencer_interests=$old->influencer_interests;

        //step 5.1
        //$campany_name_2
        $this->payment_method=$old->payment_method;
        $this->coupon=$old->coupon ?? null;
        $this->date_receipt_offers_start=$old->date_receipt_offers_start;
        $this->date_receipt_offers_end=$old->date_receipt_offers_end;
        $this->campain_proposed_budget=$old->campain_proposed_budget;
        $this->campain_director_name=$old->campain_director_name;
        $this->campain_director_email=$old->campain_director_email;
        $this->campain_director_phone=$old->campain_director_phone;
        $this->campany_name=$old->campany_name;
        $this->campany_tax_number=$old->campany_tax_number;
        $this->campany_commercial_register=$old->campany_commercial_register;
        $this->campany_financial_officer_email=$old->campany_financial_officer_email;
        $this->campany_financial_officer_phone=$old->campany_financial_officer_phone;

       //  $this->reset();
       //  dd(1);

       $this->campany_street=$old->campany_street;
       $this->campany_city=$old->campany_city;
       $this->campany_zone=$old->campany_zone;
       $this->campany_code_postal=$old->campany_code_postal;
       $this->campany_country=$old->campany_country;
        // $old->save();
        // $this->successMessage = 'Product Created Successfully.';
        $this->currentStep = 1;
    }

}

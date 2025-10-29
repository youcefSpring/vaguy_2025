<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Models\Hiring;
use App\Models\HiringConversation;
use App\Models\Transaction;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Campaingn;
use App\Models\CampainInfluencerOffer;
use App\Models\CampainOfferNotification;
use App\Models\Fichier;
use App\Models\Influencer;
use App\Services\EmailService;

class CampaignController extends Controller
{
    public $pageTitle;
    public function index() {
        $this->pageTitle = 'All Hiring';
        return $this->filterHiring();
    }

    public function change_offer_status(Request $r,$id,$status){
        $a=CampainInfluencerOffer::where('id',$id)->first();
        $camapin=Campaingn::where('id',$a->campain_id)->first();
        $a->status=$status;
        //  if(isset($r->reason)){
        //     $a->reason=$r->reason;
        //  }
        $a->save();

        $cn=new CampainOfferNotification();
        $cn->campain_offer_id=$id;
        $cn->influencer_id=$a->influencer_id;
        $cn->user_id=$camapin->user_id;
        $cn->title="Influenceur a ".authInfluencer()->fullname." votre offre";
        $cn->save();
        $user=User::find($camapin->user_id);

        $details=[
            'title' => 'Campain Offer Notifications',
            'body' => "Influenceur a ".authInfluencer()->fullname." votre offre",
            'subject' => 'Campain Offer Notifications #'.$camapin->id
      ];
        $destinations=[$user->email];
        (new EmailService())->sendEmail($details, $destinations);
        return redirect()->route('influencer.campain.index');
    }

    public function pending() {
        $this->pageTitle = 'Pending Hiring';
        return $this->filterHiring('pending');
    }

    public function inprogress() {
        $this->pageTitle = 'Processing Hiring';
        return $this->filterHiring('inprogress');
    }

    public function jobDone() {
        $this->pageTitle = 'Job Done Hiring';
        return $this->filterHiring('JobDone');
    }

    public function completed() {
        $this->pageTitle = 'Completed Hiring';
        return $this->filterHiring('completed');
    }

    public function reported() {
        $this->pageTitle = 'Reported Hiring';
        return $this->filterHiring('reported');
    }

    public function cancelled() {
        $this->pageTitle = 'Cancelled Hiring';
        return $this->filterHiring('cancelled');
    }

    protected function filterHiring($scope = null) {

        // $campains = Campaingn::with('user')->with('campain_offers');
        //  return authInfluencer();
        $authInfluencerId = authInfluencer()->id; // Assuming the authenticated user is the influencer

        $campains = Campaingn::with('user')
            ->with(['campain_offers' => function($query) use ($authInfluencerId) {
                $query->where('influencer_id', $authInfluencerId);
            }]);

        $pageTitle = "Campain List";
        //  return $hirings;

        if ($scope) {
            $campains = $campains->where('status',$scope);
        }
        $campains=$campains->latest()->paginate(getPaginate());
        $influencer   = Influencer::where('id', authInfluencerId())->with('education', 'qualification', 'socialLink', 'categories')->firstOrFail();
        return view('templates.basic.influencer.campaigns.campaigns', compact('pageTitle', 'campains', 'influencer'));

        $influencerId = authInfluencerId();
        $hirings      = Campaingn::latest()->paginate(getPaginate());
        $pageTitle = $this->pageTitle;
        $pendingHiring = Hiring::pending()->whereNotNull('influencer_id')->count();

        return view($this->activeTemplate . 'influencer.campain.list', compact('pageTitle', 'hirings', 'pendingHiring'));
        if ($scope) {
            $hirings = $hirings->$scope();
        }

        $request = request();

        if ($request->search) {
            $search  = request()->search;
            $hirings = $hirings->where(function ($q) use ($search) {
                $q->where('hiring_no', $search)->orWhereHas('user', function ($query) use ($search) {
                    $query->where('username', $search);
                });
            });
        }

        $hirings = $hirings->where('influencer_id', $influencerId)->with('user')->latest()->paginate(getPaginate());

        $pageTitle = $this->pageTitle;

        $pendingHiring = Hiring::pending()->where('influencer_id', $influencerId)->count();

        return view($this->activeTemplate . 'influencer.campain.list', compact('pageTitle', 'hirings', 'pendingHiring'));
    }

    public function detail($locale, $id) {
        $pageTitle     = 'campain Detail';
        $hiring        = Campaingn::with('user')->findOrFail($id);
        $campain        = Campaingn::with('user')->findOrFail($id);
        $conversations = HiringConversation::whereNotNull('hiring_id')->orderBy('id', 'desc')->take(10)->get();

        return view('templates.basic.influencer.campaigns.detail', compact('pageTitle', 'campain', 'hiring', 'conversations'));
    }

    public function upload_result(Request $r,$offer_id){

        $old=CampainInfluencerOffer::whereId($offer_id)->first() ?? null;
        $campain=Campaingn::find($old->campain_id);

        if ($r->hasFile('campain_result_files')) {
            $files = $r->file('campain_result_files');

            foreach ($files as $file) {
                // Get the original name of the file
                $originalName = time().'_'.$file->getClientOriginalName();

                // Define the destination path
                $destinationPath = 'campain_offers/'.$old->campain_id.'/'.$old->id;

                // Move the file to the public path with its original name
               $file->move(public_path($destinationPath), $originalName);
               $fichier = new Fichier();
               $fichier->model="CampainInfluencerOffer";
               $fichier->model_id=$old->id;
               $fichier->path=$destinationPath.'/'.$originalName;
               $fichier->save();

            }
            $user=User::find($campain->user_id);
            $notify[] = ['success', trans('files uploaded successfully')];

            $details=[
                'title' => 'Campain Offer Notifications',
                'body' => "Influenceur ".authInfluencer()->fullname." a uploadé les résultats pour votre offre",
                'subject' => 'Campain Offer Notifications #'.$campain->id
          ];

          $destinations=[$user->email];
          (new EmailService())->sendEmail($details, $destinations);
        return back()->withNotify($notify);
        } else {
            return "No files selected.";
        }

    }
    public function post_offer(Request $r){
        // return  $r;

        $influencer_id = authInfluencerId();
        $campain_id=$r->campain_id;

        $old=CampainInfluencerOffer::where('campain_id',$campain_id)
                                   ->where('influencer_id',$influencer_id)
                                   ->first() ?? null;
        if(isset($old)){
            $old->price=$r->offer;
            $old->save();
        }else{
             $old= new CampainInfluencerOffer();
             $old->campain_id=$campain_id;
             $old->influencer_id=$influencer_id;
             $old->price=$r->offer;
             $old->save();
        }
        $campain=Campaingn::find($old->campain_id);
        $user=User::find($campain->user_id);
        $cn=new CampainOfferNotification();
        $cn->campain_offer_id=$old->id;
        $cn->influencer_id=$old->influencer_id;
        $cn->user_id=$campain->user_id;
        $cn->title="Influenceur ".authInfluencer()->fullname." a proposé une offre";
        $cn->save();

        $details=[
            'title' => 'Campain Offer Notifications',
             'body' => $cn->title,
             'subject' => 'Campain Offer Notifications #'.$campain->id
      ];

      $destinations=[$user->email];
      (new EmailService())->sendEmail($details, $destinations);
        $notify[] = ['success', trans('تم تسجيل العرض بنجاح')];
        return back()->withNotify($notify);
    }

    public function confirm_offer(Request $r){


        $influencer_id = authInfluencerId();

        $campain_id=$r->campain_id;
        $old=CampainInfluencerOffer::where('campain_id',$campain_id)->where('influencer_id',$influencer_id)->first() ?? null;
        $campain=Campaingn::find($old->campain_id);
        // return $old;
        if(isset($old)){
            $old->status=$r->status;
            $old->save();
        }
        $notify[] = ['success', trans('تم تأكيد العرض بنجاح')];
        $cn=new CampainOfferNotification();
        $cn->campain_offer_id=$old->id;
        $cn->influencer_id=$old->influencer_id;
        $cn->user_id= $campain->user_id;
        $cn->title="Influenceur  ".authInfluencer()->fullname." a confirmé l'offre";
        $cn->save();
        $user=User::find($campain->user_id);
        $details=[
            'title' =>'Campain Offer Notifications',
             'body' =>$cn->title,
             'subject' =>'Campain Offer Notifications #'.$campain->id
      ];
      $destinations=[$user->email];
      (new EmailService())->sendEmail($details, $destinations);
        return back()->withNotify($notify);
    }

    public function acceptStatus($id) {
        $influencer     = authInfluencer();
        $hiring         = Hiring::pending()->where('id', $id)->where('influencer_id', $influencer->id)->with('user')->firstOrFail();
        $hiring->status = 2;
        $hiring->save();

        $user    = $hiring->user;
        $general = gs();
        notify($user, 'HIRING_INPROGRESS', [
            'influencer'    => $influencer->username,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($hiring->amount),
            'hiring_no'     => $hiring->hiring_no,
            'title'         => $hiring->title,
        ]);
        $notify[] = ['success', trans('Hiring status has now inprogress')];
        return back()->withNotify($notify);
    }

    public function jobDoneStatus($id) {
        $influencer     = authInfluencer();
        $hiring         = Hiring::inprogress()->where('id', $id)->where('influencer_id', $influencer->id)->with('user')->firstOrFail();
        $hiring->status = 3;
        $hiring->save();

        $user    = $hiring->user;
        $general = gs();
        notify($user, 'JOB_DONE_SUCCESSFULLY', [
            'influencer'    => $influencer->username,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($hiring->amount),
            'hiring_no'     => $hiring->hiring_no,
            'title'         => $hiring->title,
        ]);
        $notify[] = ['success', trans('Job has been done successfully')];
        return back()->withNotify($notify);
    }

    public function cancelStatus($id) {
        $influencer     = authInfluencer();
        $hiring         = Hiring::where('id', $id)->where('influencer_id', $influencer->id)->with('user')->firstOrFail();
        $hiring->status = 5;
        $hiring->save();

        $user    = $hiring->user;
        $general = gs();

        $user->balance += $hiring->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $hiring->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = showAmount($hiring->amount) . $general->cur_text . ' payment refunded for hiring cancellation';
        $transaction->trx          = getTrx();
        $transaction->remark       = 'hiring_payment';
        $transaction->save();

        notify($user, 'HIRING_CANCELLED', [
            'influencer'    => $influencer->username,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($hiring->amount),
            'post_balance'  => showAmount($user->balance),
            'hiring_no'     => $hiring->hiring_no,
            'title'         => $hiring->title,
        ]);
        $notify[] = ['success', 'Hiring has been cancelled successfully'];
        return back()->withNotify($notify);
    }

    public function conversation($id) {
        $pageTitle           = 'Conversation View';
        $influencerId = authInfluencerId();

        // Get campaign with messages
        $campain = Campaingn::with('campaignMessage')->findOrFail($id);
        $user    = User::where('id', $campain->user_id)->first();
        $conversationMessage = $campain->campaignMessage->take(10);

        return view('templates.basic.influencer.campain.conversation', compact('pageTitle', 'conversationMessage', 'user', 'campain'));
    }

    public function conversationStore(Request $request, $id) {
        $campain = Campaingn::find($id);

        if (!$campain) {
            return response()->json(['error' => 'Campaign id not found.']);
        }

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = User::active()->find($campain->user_id);

        if (!$user) {
            return response()->json(['error' => 'User is banned from admin.']);
        }

        $message                = new HiringConversation();
        $message->hiring_id     = $campain->id;
        $message->user_id       = $user->id;
        $message->influencer_id = authInfluencerId();
        $message->sender        = 'influencer';
        $message->message       = $request->message;

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {
                try {
                    $arrFile[] = fileUploader($file, getFilePath('conversation'));
                } catch (\Exception$exp) {
                    return response()->json(['error' => 'Couldn\'t upload your image']);
                }

            }

            $message->attachments = json_encode($arrFile);
        }

        $message->save();
        return view($this->activeTemplate . 'influencer.conversation.last_message', compact('message'));
    }

    public function conversationMessage(Request $request) {
        $conversationMessage = HiringConversation::where('hiring_id', $request->campain_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'influencer.conversation.message', compact('conversationMessage'));
    }
}

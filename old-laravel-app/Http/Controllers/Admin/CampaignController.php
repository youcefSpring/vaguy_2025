<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hiring;
use App\Models\Campaingn;
use App\Models\HiringConversation;
use App\Models\Transaction;

class CampaignController extends Controller
{
    public function index()
    {
        $this->pageTitle = 'All Campain';
        return $this->filterHiring();
    }

    public function pending()
    {
        $this->pageTitle = 'Pending campain';
        return $this->filterHiring('pending');
    }

    public function inprogress()
    {
        $this->pageTitle = 'Inprogress campain';
        return $this->filterHiring('inprogress');
    }

    public function jobDone()
    {
        $this->pageTitle = 'Job Done campain';
        return $this->filterHiring('JobDone');
    }

    public function completed()
    {
        $this->pageTitle = 'Completed campain';
        return $this->filterHiring('completed');
    }

    public function reported()
    {
        $this->pageTitle = 'Reported campain';
        return $this->filterHiring('reported');
    }

    public function cancelled()
    {
        $this->pageTitle = 'Cancelled campain';
        return $this->filterHiring('cancelled');
    }

    protected function filterHiring($scope = null)
    {
        $hirings = Campaingn::with('user');
        $pageTitle = $this->pageTitle;
        //  return $hirings;

        if ($scope) {
            $hirings = $hirings->where('status',$scope);
        }
        $hirings=$hirings->latest()->paginate(getPaginate());
        return view('admin.campain.list', compact('pageTitle', 'hirings'));


        $request = request();

        if ($request->search) {
            $search  = request()->search;
            $hirings = $hirings->where(function ($q) use ($search) {
                $q->where('hiring_no', $search)->orWhereHas('user', function ($user) use ($search) {
                    $user->where('username', $search);
                })->orWhereHas('influencer', function ($influencer) use ($search) {
                    $influencer->where('username', $search);
                });
            });
        }

        $hirings = $hirings->with('user', 'influencer')->latest()->paginate(getPaginate());

        $pageTitle = $this->pageTitle;

        return view('admin.campain.list', compact('pageTitle', 'hirings'));
    }

    public function detail($id)
    {
        $pageTitle     = 'campain Detail';
        $hiring        = Campaingn::with('user')->findOrFail($id);
        $campain        = Campaingn::with('user')->findOrFail($id);
        // dd $campain->influencer_public_wilaya;
        // $conversations = HiringConversation::where('hiring_id', $hiring->id)->orderBy('id', 'desc')->take(10)->get();
        $conversations = HiringConversation::whereNotNull('hiring_id')->orderBy('id', 'desc')->take(10)->get();
        return view('admin.campain.detail', compact('pageTitle', 'hiring', 'conversations','campain'));
    }

    public function takeAction($id, $status)
    {
        $hiring = Hiring::with('user', 'influencer')->findOrFail($id);

        if ($status == 1) {
            $this->inFavourOfInfluencer($hiring);
        }

        if ($status == 6) {
            $this->inFavourOfClient($hiring);
        }

        $hiring->status = $status;
        $hiring->save();

        $notify[] = ['success', 'Action taken successfully'];
        return back()->withNotify($notify);
    }

    protected function inFavourOfClient($hiring)
    {
        $influencer = $hiring->influencer;
        $user       = $hiring->user;
        $user->balance += $hiring->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $hiring->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '+';
        $transaction->details      = 'Payment refunded due to incomplete hiring task';
        $transaction->trx          = getTrx();
        $transaction->remark       = 'payment_refunded';
        $transaction->save();

        $general = gs();

        notify($user, 'HIRING_REFUND', [
            'site_currency' => $general->cur_text,
            'title'         => $hiring->title,
            'amount'        => showAmount($hiring->amount),
            'post_balance'  => showAmount($user->balance),
            'hiring_no'     => $hiring->hiring_no,
        ]);

        notify($influencer, 'HIRING_REJECTED', [
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($hiring->amount),
            'post_balance'  => showAmount($influencer->balance),
            'hiring_no'     => $hiring->hiring_no,
            'title'         => $hiring->title,
        ]);
    }

    protected function inFavourOfInfluencer($hiring)
    {
        $influencer = $hiring->influencer;
        $user       = $hiring->user;

        $influencer->balance += $hiring->amount;
        $influencer->increment('completed_order');
        $influencer->save();

        $transaction                = new Transaction();
        $transaction->influencer_id = $influencer->id;
        $transaction->amount        = $hiring->amount;
        $transaction->post_balance  = $influencer->balance;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Payment received for completing hiring task';
        $transaction->trx           = getTrx();
        $transaction->remark        = 'payment_on_hiring';
        $transaction->save();

        $general = gs();

        $shortCodes = [
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($hiring->amount),
            'hiring_no'     => $hiring->hiring_no,
            'title'         => $hiring->title,
        ];

        notify($influencer, 'HIRING_COMPLETED_INFLUENCER', $shortCodes);
        notify($user, 'HIRING_COMPLETED_CLIENT', $shortCodes);
    }

    public function conversationStore(Request $request, $id)
    {
        $hiring    = Hiring::find($id);
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $conversation                = new HiringConversation();
        $conversation->hiring_id     = $hiring->id;
        $conversation->user_id       = $hiring->user_id;
        $conversation->influencer_id = $hiring->influencer_id;
        $conversation->admin_id      = auth()->guard('admin')->id();
        $conversation->sender        = 'admin';
        $conversation->message       = $request->message;
        $conversation->save();

        return view('admin.campain.last_message', compact('conversation'));
    }

    public function conversationMessage(Request $request)
    {
        $conversations = HiringConversation::where('hiring_id', $request->hiring_id)->take($request->messageCount)->orderBy('id', 'desc')->get();
        return view('admin.campain.conversation', compact('conversations'));
    }

    public function download($attachment)
    {
        $path = getFilePath('conversation');
        $file = $path . '/' . $attachment;
        return response()->download($file);
    }

}

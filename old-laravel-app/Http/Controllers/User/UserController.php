<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Hiring;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Wilaya;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = __('dashboard.title');
        $userId = auth()->id();
        $user = auth()->user();

        // Basic stats
        $data['current_balance'] = $user->balance;
        $data['deposit_amount'] = Deposit::where('user_id',$userId)->where('status',1)->sum('amount');
        $data['total_transaction'] = Transaction::where('user_id',$userId)->count();
        $data['total_order'] = Order::where('user_id',$userId)->count();
        $data['complete_order'] = Order::completed()->where('user_id',$userId)->count();
        $data['incomplete_order'] = Order::where('status','!=',1)->where('user_id',$userId)->count();

        // Enhanced dashboard data
        $data['total_spending'] = Transaction::where('user_id',$userId)->where('trx_type','-')->sum('amount');
        $data['this_month_orders'] = Order::where('user_id',$userId)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $data['pending_orders'] = Order::where('user_id',$userId)->where('status', 0)->count();
        $data['active_campaigns'] = \App\Models\Campaingn::where('user_id',$userId)
            ->where('campain_start_date', '<=', now())
            ->where('campain_end_date', '>=', now())
            ->count();

        // Recent activity data
        $data['recent_activities'] = collect([
            [
                'type' => 'campaign',
                'title' => 'Campaign "Summer Sale" launched',
                'description' => 'Your campaign has been successfully launched and is now live',
                'time' => '2 hours ago',
                'icon' => 'bi-megaphone',
                'color' => 'success'
            ],
            [
                'type' => 'influencer',
                'title' => 'New influencer applications',
                'description' => '3 new influencers have applied to your campaigns',
                'time' => '5 hours ago',
                'icon' => 'bi-person-plus',
                'color' => 'info'
            ],
            [
                'type' => 'payment',
                'title' => 'Payment processed',
                'description' => 'Payment of ' . $data['current_balance'] . ' has been processed for campaign expenses',
                'time' => '1 day ago',
                'icon' => 'bi-credit-card',
                'color' => 'warning'
            ]
        ]);

        // Chart data for analytics
        $data['monthly_spending'] = Transaction::where('user_id',$userId)
            ->where('trx_type','-')
            ->selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Notification data
        $data['notifications'] = collect([
            [
                'title' => 'Campaign Approved',
                'message' => 'Your campaign "Brand Launch" has been approved',
                'time' => '2h',
                'icon' => 'bi-check-circle',
                'color' => 'success',
                'read' => false
            ],
            [
                'title' => 'New Influencer Interest',
                'message' => '5 influencers showed interest in your campaign',
                'time' => '4h',
                'icon' => 'bi-person-plus',
                'color' => 'info',
                'read' => false
            ],
            [
                'title' => 'Payment Processed',
                'message' => 'Your payment of ' . gs()->cur_text . '500 has been processed',
                'time' => '1d',
                'icon' => 'bi-credit-card',
                'color' => 'warning',
                'read' => true
            ]
        ]);

        $transactions = Transaction::where('user_id',$userId)->with('user')->latest()->take(5)->get();
        $general = gs();

        // dd(1);
        return Inertia::render('User/Dashboard', [
            'pageTitle' => $pageTitle,
            'transactions' => $transactions,
            'data' => $data,
            'general' => $general,
        ]);
        // return view($this->activeTemplate . 'user.dashboard', compact('pageTitle','transactions','data','general'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $pageDescription = 'Track all your deposit transactions and payment history';
        $pageIcon = 'bi bi-wallet2';
        $breadcrumbs = [
            ['title' => 'Financial', 'url' => '#'],
            ['title' => 'Deposit History', 'url' => route('user.deposit.history')]
        ];
        $pageActions = '<a href="' . route('user.deposit') . '" class="btn btn-primary">
                           <i class="bi bi-plus-circle me-1"></i>' . __('Make Deposit') . '
                       </a>';

        $deposits = auth()->user()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx',$request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());

        return view($this->activeTemplate.'user.deposit_history', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'pageActions',
            'deposits'
        ));
    }

    public function show2faForm()
    {
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Security';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transaction History';
        $pageDescription = 'View and filter all your financial transactions';
        $pageIcon = 'bi bi-receipt';
        $breadcrumbs = [
            ['title' => 'Financial', 'url' => '#'],
            ['title' => 'Transactions', 'url' => route('user.transactions')]
        ];
        $pageActions = '<a href="' . route('user.deposit') . '" class="btn btn-primary">
                           <i class="bi bi-plus-circle me-1"></i>' . __('Add Funds') . '
                       </a>';

        $remarks = Transaction::where('user_id',auth()->id())->distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id',auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx',$request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type',$request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark',$request->remark);
        }

        $transactions = $transactions->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.transactions', compact('pageTitle','pageDescription','pageIcon','breadcrumbs','pageActions','transactions','remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == 2) {
            $notify[] = ['error','Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == 1) {
            $notify[] = ['error','You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        return view($this->activeTemplate.'user.kyc.form', compact('pageTitle'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate.'user.kyc.info', compact('pageTitle','user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act','kyc')->first();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);
        $user = auth()->user();
        $user->kyc_data = $userData;
        $user->kv = 2;
        $user->save();

        $notify[] = ['success','KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);

    }

    public function attachmentDownload($fileHash)
    {
        try {
            $filePath = decrypt($fileHash);

            // Check if file exists
            if (!file_exists($filePath)) {
                $notify[] = ['error', 'File not found'];
                return back()->withNotify($notify);
            }

            // Check if file is readable
            if (!is_readable($filePath)) {
                $notify[] = ['error', 'File is not accessible'];
                return back()->withNotify($notify);
            }

            $extension = pathinfo($filePath, PATHINFO_EXTENSION);
            $general = gs();
            $title = slug($general->site_name).'- attachments.'.$extension;

            // Get mime type safely
            $mimetype = $this->getMimeType($filePath, $extension);

            // Set headers for file download
            return response()->download($filePath, $title, [
                'Content-Type' => $mimetype,
                'Content-Disposition' => 'attachment; filename="' . $title . '"'
            ]);

        } catch (\Exception $e) {
            \Log::error('Attachment download error: ' . $e->getMessage());
            $notify[] = ['error', 'Unable to download file'];
            return back()->withNotify($notify);
        }
    }

    /**
     * Get MIME type safely with fallback
     */
    private function getMimeType($filePath, $extension)
    {
        // Try to get mime type using multiple methods
        if (function_exists('mime_content_type') && file_exists($filePath)) {
            try {
                $mimeType = mime_content_type($filePath);
                if ($mimeType) {
                    return $mimeType;
                }
            } catch (\Exception $e) {
                // Fall through to extension-based detection
            }
        }

        // Fallback to extension-based mime type detection
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'txt' => 'text/plain',
            'zip' => 'application/zip',
            'rar' => 'application/rar',
            'mp4' => 'video/mp4',
            'mp3' => 'audio/mpeg',
            'csv' => 'text/csv',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xls' => 'application/vnd.ms-excel',
        ];

        $extension = strtolower($extension);
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    public function userData()
    {
        // return 1;
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'Client Data';
        $wilayas=Wilaya::get(['id','code','name']);
        // return $wilayas;
        return view($this->activeTemplate.'user.user_data', compact('pageTitle','user','wilayas'));
    }

    public function userDataSubmit(Request $request)
    {
        // return $request;
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country'=>@$user->address->country,
            'address'=>$request->address,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'city'=>$request->city,
        ];
        $user->reg_step = 1;
        $user->save();

        $notify[] = ['success','Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);

    }

}

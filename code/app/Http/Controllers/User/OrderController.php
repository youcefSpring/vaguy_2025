<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Influencer;
use App\Models\Order;
use App\Models\OrderConversation;
use App\Models\Service;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function all(Request $request)
    {

        $pageTitle = 'All Orders';
        $orders    = Order::paymentCompleted()->where('user_id', auth()->id());

        if ($request->search) {
            $search = $request->search;
            $orders = $orders->where(function ($q) use ($search) {
                $q->where('order_no', $search)->orWhereHas('influencer', function ($query) use ($search) {
                    $query->where('username', $search);
                });
            });
        }

        $orders = $orders->with('influencer', 'review', 'service')->latest()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.order.list', compact('pageTitle', 'orders'));
    }

    public function detail($locale, $id)
    {
        $pageTitle = 'Order Detail';
        $order     = Order::where('user_id', auth()->id())->with('influencer', 'service')->findOrFail($id);
        return view($this->activeTemplate . 'user.order.detail', compact('pageTitle', 'order'));
        // order is object 
        // {
        //     "id": 9,==> int
        //     "user_id": 99,==> int
        //     "influencer_id": 614,==> int
        //     "service_id": 7,==> int
        //     "order_no": "XFYYS6QYAE6Y",==> string
        //     "title": "test test",==> string
        //     "delivery_date": "2024-03-02",==> date
        //     "amount": "10000.00000000", ==> decimal
        //     "description": "FFFFF",==> string
        //     "status": 0,==> int
        //     "payment_status": 1,==> int
        //     "reason": null, ==> string
        //     "created_at": "2024-02-28T13:05:41.000000Z",==> timestamp
        //     "updated_at": "2024-02-28T13:05:41.000000Z",==> timestamp
        //     "influencer": {
        // object of influencer
        //     },
        //     "service": {
        //         object of service
        // }
        return view($this->activeTemplate . 'user.order.detail', compact('pageTitle', 'order'));
    }

    public function order($locale, $id)
    {
        $service = Service::approved()
            ->whereHas('influencer', function ($influencer) {
                return $influencer->active();
            })
            ->where('id', $id)
            ->firstOrFail();
        $pageTitle  = 'Order a Service';
        $influencer = $service->influencer;
        return view($this->activeTemplate . 'user.order.form', compact('pageTitle', 'service', 'influencer'));
    }

    public function orderConfirm(Request $request, $influencerId, $serviceId)
    {

        // dd($request->all(),$influencerId, $serviceId);
        $service    = Service::approved()->where('id', $serviceId)->where('influencer_id', $influencerId)->firstOrFail();
        $influencer = Influencer::active()->findOrFail($influencerId);

        $request->validate([
            'title'         => 'required|string|max:255',
            // 'delivery_date' => 'required|date_format:Y-m-d|after:yesterday',
            'description'   => 'required|string',
            'payment_type'  => 'required|in:1,2',
        ]);
        
        
        $user = auth()->user();
        
        if ($request->payment_type == 1 && $service->price > $user->balance) {
            $notify[] = ['error', 'You have no sufficient balance'];
            // dd($user);
            return back()->withNotify($notify)->withInput();
        }
        
        $paymentStatus = $request->payment_type == 1 ? 1 : 0;
        $order = $this->saveOrderData($influencer, $service, $paymentStatus);

        if ($request->payment_type == 1) {
            $this->payViaWallet($order, $service);
            // dd(1);
        } else {
            session()->put('payment_data', [
                'order_id' => $order->id,
                'amount' => $service->price,
            ]);
            // dd(2);
        return redirect()->route('payment');
        }

        // dd($request->all());

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'A new order placed by ' . $user->username;
        $adminNotification->click_url = urlPath('admin.order.detail', $order->id);
        $adminNotification->save();

        $general = gs();

        notify($influencer, 'ORDER_PLACED', [
            'username'      => $user->username,
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
        ]);

        $notify[] = ['success', 'Your order submitted successfully'];

        return redirect()->route('user.order.all');
        return to_route('user.order.all')->withNotify($notify);
    }

    protected function saveOrderData($influencer, $service, $paymentStatus)
    {
        $request = request();
        $user    = auth()->user();

        $order                 = new Order();
        $order->user_id        = $user->id;
        $order->influencer_id  = $influencer->id;
        $order->service_id     = $service->id;
        $order->title          = $request->title;
        $order->delivery_date  = $request->delivery_date;
        $order->amount         = $service->price;
        $order->description    = $request->description;
        $order->payment_status = $paymentStatus;
        $order->order_no       = getTrx();
        $order->save();

        return $order;
    }


    protected function payViaWallet($order, $service)
    {
        $user = auth()->user();
        $user->balance -= $service->price;
        $user->save();


        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $order->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '-';
        $transaction->trx          = getTrx();
        $transaction->details      = 'Balance deducted for ordering a service.';
        $transaction->remark       = 'order_payment';
        $transaction->save();
    }



    public function completeStatus($id)
    {
        $user          = auth()->user();
        $order         = Order::JobDone()->where('id', $id)->where('user_id', $user->id)->with('influencer')->firstOrFail();
        $order->status = 1;
        $order->save();

        $influencer = $order->influencer;
        $general    = gs();

        $influencer->balance += $order->amount;
        $influencer->increment('completed_order');
        $influencer->save();

        notify($influencer, 'ORDER_COMPLETED_INFLUENCER', [
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
        ]);

        $transaction                = new Transaction();
        $transaction->influencer_id = $influencer->id;
        $transaction->amount        = $order->amount;
        $transaction->post_balance  = $influencer->balance;
        $transaction->trx_type      = '+';
        $transaction->details       = 'Payment received for completing a new service order';
        $transaction->trx           = getTrx();
        $transaction->remark        = 'order_payment';
        $transaction->save();

        $notify[] = ['success', 'Order completed successfully'];
        return back()->withNotify($notify);
    }

    public function reportStatus(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string',
        ]);

        $user          = auth()->user();
        $order         = Order::where('id', $id)->where('user_id', $user->id)->with('influencer')->firstOrFail();
        $order->status = 4;
        $order->reason = $request->reason;
        $order->save();

        $influencer = $order->influencer;
        $general    = gs();

        notify($influencer, 'ORDER_REPORTED', [
            'username'      => $user->username,
            'title'         => $order->title,
            'site_currency' => $general->cur_text,
            'amount'        => showAmount($order->amount),
            'order_no'      => $order->order_no,
            'reason'        => $order->reason,
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'This order is reported by ' . $user->username;
        $adminNotification->click_url = urlPath('admin.order.detail', $order->id);
        $adminNotification->save();

        $notify[] = ['success', 'You report submitted successfully. Admin will take action immediately.'];
        return back()->withNotify($notify);
    }

    public function conversation($id)
    {
        $pageTitle           = 'Order Conversation';
        $order               = Order::where('user_id', auth()->id())->with('orderMessage')->findOrFail($id);
        $influencer          = Influencer::where('id', $order->influencer_id)->first();
        $conversationMessage = $order->orderMessage->take(10);
        return view($this->activeTemplate . 'user.order.conversation', compact('pageTitle', 'conversationMessage', 'influencer', 'order'));
        // return 4 variables
        // {
        //    1. "pageTitle": "Order Conversation",
        //     2. "order": {
        //         "id": 9,==> int
        //         "user_id": 99,==> int
        //         "influencer_id": 614,==> int
        //         "service_id": 7,==> int
        //         "order_no": "XFYYS6QYAE6Y",==> string
        //         "title": "test test",==> string
        //         "delivery_date": "2024-03-02",
        //         "amount": "10000.00000000",==> decimal
        //         "description": "FFFFF",==> string
        //         "status": 0,==> int
        //         "payment_status": 1,==> int
        //         "reason": null,==> string
        //         "created_at": "2024-02-28T13:05:41.000000Z",==> timestamp
        //         "updated_at": "2024-02-28T13:05:41.000000Z",==> timestamp
        //         "order_message": [ // array of order_message
        //             {
        //                 "id": 2,==> string
        //                 "order_id": 9,==> string
        //                 "user_id": 99,==> string
        //                 "influencer_id": 614,==> string
        //                 "admin_id": 0,==> string
        //                 "sender": "client", ==> string 
        //                 "message": "ssss", ==> string 
        //                 "attachments": "[\"65df437937a9d1709130617.jpeg\"]", ==> string (array transformed to string)
        //                 "created_at": "2024-02-28T14:30:17.000000Z",==> timestamp
        //                 "updated_at": "2024-02-28T14:30:17.000000Z"==> timestamp
        //             },
        //             {
        //                 "id": 1,
        //                 "order_id": 9,
        //                 "user_id": 99,
        //                 "influencer_id": 614,
        //                 "admin_id": 0,
        //                 "sender": "client",
        //                 "message": "sss",
        //                 "attachments": null,
        //                 "created_at": "2024-02-28T14:30:08.000000Z",
        //                 "updated_at": "2024-02-28T14:30:08.000000Z"
        //             }
        //         ]
        //     },
        //     "influencer": {
        //       object of influencer
        //     },
        //     "conversationMessage": [  // array of order_message
        //         {
        //              "id": 2,==> string
        //                 "order_id": 9,==> string
        //                 "user_id": 99,==> string
        //                 "influencer_id": 614,==> string
        //                 "admin_id": 0,==> string
        //                 "sender": "client", ==> string 
        //                 "message": "ssss", ==> string 
        //                 "attachments": "[\"65df437937a9d1709130617.jpeg\"]", ==> string (array transformed to string)
        //                 "created_at": "2024-02-28T14:30:17.000000Z",==> timestamp
        //                 "updated_at": "2024-02-28T14:30:17.000000Z"==> timestamp
        //         },
        //         {
        //             "id": 1,
        //             "order_id": 9,
        //             "user_id": 99,
        //             "influencer_id": 614,
        //             "admin_id": 0,
        //             "sender": "client",
        //             "message": "sss",
        //             "attachments": null,
        //             "created_at": "2024-02-28T14:30:08.000000Z",
        //             "updated_at": "2024-02-28T14:30:08.000000Z"
        //         }
        //     ]
        // }
        return view($this->activeTemplate . 'user.order.conversation', compact('pageTitle', 'conversationMessage', 'influencer', 'order'));
    }

    public function conversationStore(Request $request, $id)
    {

        $order = Order::where('user_id', auth()->id())->find($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found']);
        }

        $validator = Validator::make($request->all(), [
            'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $influencer = Influencer::where('id', $order->influencer_id)->first();

        $message                = new OrderConversation();
        $message->order_id      = $order->id;
        $message->user_id       = auth()->id();
        $message->influencer_id = $influencer->id;
        $message->sender        = 'client';
        $message->message       = $request->message;

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {
                try {
                    $arrFile[] = fileUploader($file, getFilePath('conversation'));
                } catch (\Exception $exp) {
                    return response()->json(['error' => 'Couldn\'t upload your image']);
                }
            }

            $message->attachments = json_encode($arrFile);
        }

        $message->save();

        return view($this->activeTemplate . 'user.conversation.last_message', compact('message'));
    }

    public function conversationMessage(Request $request)
    {
        $conversationMessage = OrderConversation::where('order_id', $request->order_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'user.conversation.message', compact('conversationMessage'));
    }
}

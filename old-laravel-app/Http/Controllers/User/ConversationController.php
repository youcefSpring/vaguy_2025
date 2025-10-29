<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Models\Influencer;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ConversationController extends Controller {

    public function create($id) {
        $influencer = Influencer::select('id', 'username', 'status', 'last_seen')->find($id);
        $conversation = Conversation::where('user_id', auth()->id())->where('influencer_id', $id)->first();

        if (!$conversation) {
            $conversation = new Conversation();
            $conversation->user_id = auth()->id();
            $conversation->influencer_id = $id;
            $conversation->save();
        }

        return redirect()->route('user.conversation.view', ['id' => $conversation->id]);
    }

    public function index(Request $request) {
        $pageTitle = 'Messages';
        $pageDescription = 'Communicate with influencers and manage your conversations';
        $pageIcon = 'bi bi-chat-dots';
        $breadcrumbs = [
            ['title' => 'Communication', 'url' => '#'],
            ['title' => 'Messages', 'url' => route('user.conversation.index')]
        ];

        $conversations = Conversation::where('user_id', auth()->id());
        if($request->search){
            $search = $request->search;
            $conversations = $conversations->WhereHas('influencer', function ($influencer) use ($search) {
                $influencer->where('username', 'like', "%$search%")->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%");
            });
        }
        $conversations = $conversations->with(['influencer', 'lastMessage'])->whereHas('lastMessage')->latest()->paginate(getPaginate());

        return view($this->activeTemplate . 'user.conversation.index', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'conversations'
        ));
    }

    public function store(Request $request, $id) {

        // dd($request);
        $validator = Validator::make($request->all(), [
            // 'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg','PNG', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $message                  = new ConversationMessage();
        $message->conversation_id = $id;
        $message->sender          = 'client';
        $message->message         = $request->message ?? "";

        // if ($request->hasFile('attachments')) {

        //     foreach ($request->file('attachments') as $file) {
        //         try {
        //             $arrFile[] = fileUploader($file, getFilePath('conversation'));
        //         } catch (\Exception$exp) {
        //             return response()->json(['error' => $exp]);
        //         }

        //     }
        //     $message->attachments = json_encode($arrFile);
        // }
        if ($request->hasFile('attachments')) {
            $arrFile = [];

            foreach ($request->file('attachments') as $file) {
                try {
                    // Define the destination path in the public folder
                    $destinationPath =  getFilePath('conversation');

                    // Ensure the destination directory exists
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    // Generate a unique file name
                    $fileName = uniqid() . '_' . $file->getClientOriginalName();

                    // Move the file to the public/attachments directory
                    $file->move($destinationPath, $fileName);

                    // Add the relative file path to the array
                    // $arrFile[] = 'attachments/' . $fileName;
                    $arrFile[] =  $fileName;
                } catch (\Exception $exp) {
                    return response()->json(['error' => $exp->getMessage()], 500);
                }
            }

            $message->attachments = json_encode($arrFile);
        }

        $message->save();
        return redirect()->route('user.conversation.view', ['id' => $id]);
    }

    public function view(Request $request, $id) {
        $conversation = Conversation::where('user_id', auth()->id())->where('id', $id)->with('influencer', 'messages')->first();
        $influencer = $conversation->influencer;

        $pageTitle = 'Chat with @' . $influencer->username;
        $pageDescription = 'Direct messaging with ' . $influencer->firstname . ' ' . $influencer->lastname;
        $pageIcon = 'bi bi-chat-square-text';
        $breadcrumbs = [
            ['title' => 'Communication', 'url' => '#'],
            ['title' => 'Messages', 'url' => route('user.conversation.index')],
            ['title' => '@' . $influencer->username, 'url' => route('user.conversation.view', $id)]
        ];

        $conversations = Conversation::where('user_id', auth()->id());
        if($request->search){
            $search = $request->search;
            $conversations = $conversations->WhereHas('influencer', function ($influencer) use ($search) {
                $influencer->where('username', 'like', "%$search%")->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%");
            });
        }
        $conversations = $conversations->with(['influencer', 'lastMessage'])->whereHas('lastMessage')->latest()->paginate(getPaginate());

        $conversationMessage = [];
        if($request->more){
            $conversationMessage = $conversation->messages->take($request->more);
        } else {
            $conversationMessage = $conversation->messages->take(10);
        }
        $messagesLength = count($conversation->messages);

        return view($this->activeTemplate . 'user.conversation.view', compact(
            'pageTitle',
            'pageDescription',
            'pageIcon',
            'breadcrumbs',
            'conversation',
            'conversationMessage',
            'influencer'
        ));
    }

    public function message(Request $request){
        $conversationMessage = ConversationMessage::where('conversation_id',$request->conversation_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'user.conversation.message', compact('conversationMessage'));
    }
}

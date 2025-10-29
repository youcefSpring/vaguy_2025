<?php

namespace App\Http\Controllers\Influencer;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationMessage;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ConversationController extends Controller {

    public function index(Request $request) {
        $pageTitle     = 'Conversation List';
        $conversations = Conversation::where('influencer_id', authInfluencerId());

        if ($request->search) {
            $search        = $request->search;
            $conversations = $conversations->WhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%")->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%");
            });
        }

        $conversations = $conversations->with(['user', 'lastMessage'])->whereHas('lastMessage')->latest()->paginate(getPaginate());
        return view('templates.basic.influencer.conversation.conversations', compact('pageTitle', 'conversations'));
    }

    public function store(Request $request, $id) {

        $validator = Validator::make($request->all(), [
            // 'message'       => 'required',
            'attachments'   => 'nullable|array',
            'attachments.*' => ['required', new FileTypeValidate(['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'txt'])],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $message                  = new ConversationMessage();
        $message->conversation_id = $id;
        $message->sender          = 'influencer';
        $message->message         = $request->message;

        // if ($request->hasFile('attachments')) {

        //     foreach ($request->file('attachments') as $file) {
        //         try {
        //             $arrFile[] = fileUploader($file, getFilePath('conversation'));
        //         } catch (\Exception$exp) {
        //             return response()->json(['error' => 'Couldn\'t upload your image']);
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
                    $arrFile[] = $fileName;
                } catch (\Exception $exp) {
                    return response()->json(['error' => $exp->getMessage()], 500);
                }
            }

            $message->attachments = json_encode($arrFile);
        }

        $message->save();
        return redirect()->route('influencer.conversation.view', ['id' => $id]);
    }

    public function view(Request $request , $id) {
        $pageTitle           = 'Conversation with Client';
        $conversation        = Conversation::where('influencer_id', authInfluencerId())->where('id', $id)->with('user', 'messages')->first();
        $user                = $conversation->user;
        $conversationMessage = [] ;
        if($request->more){
            $conversationMessage = $conversation->messages->take($request->more);
        }else $conversationMessage = $conversation->messages->take(10);
        $conversations = Conversation::where('influencer_id', authInfluencerId());

        if ($request->search) {
            $search        = $request->search;
            $conversations = $conversations->WhereHas('user', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%")->orWhere('firstname', 'like', "%$search%")->orWhere('lastname', 'like', "%$search%");
            });
        }

        $conversations = $conversations->with(['user', 'lastMessage'])->whereHas('lastMessage')->latest()->paginate(getPaginate());
        $messagesLength = count($conversation->messages);
        $currentConversation = $conversation;
        $messages = $conversationMessage;
        return view('templates.basic.influencer.conversation.conversations', compact('pageTitle', 'conversations', 'currentConversation', 'messages'));
    }

    public function message(Request $request){
        $conversationMessage = ConversationMessage::where('conversation_id',$request->conversation_id)->take($request->messageCount)->latest()->get();
        return view($this->activeTemplate . 'influencer.conversation.message', compact('conversationMessage'));
    }

    public function download($id) {
        $message = ConversationMessage::where('id', $id)->first();

        if (!$message || !$message->attachments) {
            abort(404);
        }

        $attachments = json_decode($message->attachments, true);

        if (empty($attachments)) {
            abort(404);
        }

        $filename = $attachments[0]; // Download first attachment
        $path = getFilePath('conversation') . '/' . $filename;
        $fullPath = public_path($path);

        if (!file_exists($fullPath)) {
            abort(404);
        }

        return response()->download($fullPath, $filename);
    }

}

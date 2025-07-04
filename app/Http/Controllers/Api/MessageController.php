<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;

class MessageController extends Controller
{
    public function conversations(Request $request)
    {
        $user = $request->user();

        $conversations = Conversation::where('user_id', $user->id)
            ->with(['latestMessage', 'receiver:id,name,email'])
            ->latest()
            ->get();

        return response()->json($conversations);
    }

    public function messages($id)
    {
        $conversation = Conversation::findOrFail($id);

        $messages = Message::where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request, $id)
    {
        $request->validate([
            'text' => 'required|string',
        ]);

        $conversation = Conversation::findOrFail($id);

        $message = Message::create([
            'conversation_id' => $id,
            'sender_id'       => $request->user()->id,
            'text'            => $request->text,
        ]);

        return response()->json([
            'message' => 'Message sent',
            'data'    => $message
        ], 201);
    }
}

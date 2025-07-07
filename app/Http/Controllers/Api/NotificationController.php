<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\GlobalAnnouncement;

class NotificationController extends Controller
{
    public function updateToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user = $request->user();
        $user->fcm_token = $request->fcm_token;
        $user->save();

        return response()->json(['message' => 'Token updated']);
    }
       public function broadcastGlobal(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'body'  => 'required|string',
        ]);

        $users = \App\Models\User::whereNotNull('fcm_token')->get();
        Notification::send($users, new GlobalAnnouncement($request->title, $request->body));

        return response()->json(['message' => 'Broadcast sent']);
    }
   
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Donation;


class DashboardController extends Controller
{
       public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'donor') {
            $donations = $user->donations()->latest()->get();
        }

        elseif ($user->role === 'org') {
            $donations = Donation::where('statua', 'متاح')
                                  ->with('user:id,name,email')
                                  ->latest()
                                  ->get();
        }

        elseif ($user->role === 'receiver') {
            $donations = Donation::where('statua', 'متاح')->latest()->get();
        }

        else {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        return response()->json([
            'user' => $user,
            'dashboard' => $donations
        ]);
    }
}

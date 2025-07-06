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

        if ($user->role === 'org') {
            $status = [
                'total'    => Donation::count(),
                'accepted' => Donation::where('status', 'مقبول')->count(),
                'pending'  => Donation::where('status', 'قيد المراجعة')->count(),
                'messages' => 3 // يمكنك تعديلها لاحقًا
            ];

            $recent = Donation::latest()->take(5)->get()->map(function ($d) {
                return [
                    'title'     => $d->title,
                    'status'    => $d->status,
                    'time_ago'  => $d->created_at->diffForHumans(),
                ];
            });

            return response()->json([
                'status' => $status,
                'recent_donations' => $recent,
            ]);
        }

        elseif (in_array($user->role, ['donor', 'receiver'])) {
            $donations = $user->donations()->latest()->get();

            $status = [
                'total'       => $donations->count(),
                'completed'   => $donations->where('status', 'مكتمل')->count(),
                'in_progress' => $donations->where('status', '!=', 'مكتمل')->count(),
            ];

            $formatted = $donations->map(function ($d) {
                return [
                    'id'         => $d->id,
                    'title'      => $d->title,
                    'status'     => $d->status,
                    'created_at' => $d->created_at->diffForHumans(),
                ];
            });

            return response()->json([
                'status'     => $status,
                'donations' => $formatted,
            ]);
        }

        return response()->json(['message' => 'غير مصرح'], 403);
}

}

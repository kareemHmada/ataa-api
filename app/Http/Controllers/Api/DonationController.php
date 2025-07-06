<?php

namespace App\Http\Controllers\Api;

use App\Models\Donation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonationController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'donor') {
            $donations = Donation::where('user_id', $user->id)->latest()->get();
        } elseif ($user->role === 'org' || $user->role === 'receiver') {
            $donations = Donation::where('status', 'متاح')->latest()->get();
        } else {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        return response()->json($donations);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|date',
            'description' => 'required|string',
            'category'    => 'required|string',
            'status'      => 'required|string',
            'img'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->except('img');
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('donations', $filename, 'public');
            $data['img'] = 'donations/' . $filename;
        }

        $donation = Donation::create($data);

        return response()->json([
            'message' => 'تم إنشاء التبرع بنجاح',
            'donation' => $donation
        ], 201);
    }
}

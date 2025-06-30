<?php

namespace App\Http\Controllers\Api;

use App\Models\Donation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonationController extends Controller
{
   public function index(Request $request)
{
    $user = $request->user(); // المستخدم الحالي

    if ($user->isDonor()) {
        // المتبرع يشوف تبرعاته فقط
        $donations = Donation::where('user_id', $user->id)->get();
    }

    elseif ($user->isOrg()) {
        // الجمعية تشوف كل التبرعات المتاحة
        $donations = Donation::where('statua', 'متاح')->get();
    }

    elseif ($user->isReceiver()) {
        // المستفيد يشوف التبرعات المتاحة فقط
        $donations = Donation::where('statua', 'متاح')->get();
    }

    else {
        return response()->json(['message' => 'غير مصرح'], 403);
    }

    return response()->json($donations);
}

    public function store(Request $request)
    {

        
         $request->validate([
        'title'       => 'required|string',
        'date'        => 'required|date',
        'description' => 'required|string',
        'category'    => 'required|string',
        'statua'      => 'required|string',
        'img'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = $request->except('img');
    $data['user_id'] = $request->user()->id;

    if ($request->hasFile('img')) {
        $file     = $request->file('img');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs('public/donations', $filename);
        $data['img'] = 'donations/' . $filename;
    }

    $donation = Donation::create($data);

    return response()->json($donation, 201);
    }
}

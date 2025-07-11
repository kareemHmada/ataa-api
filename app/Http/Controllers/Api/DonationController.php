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
        } else {
            $donations = Donation::latest()->get();
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
            'img'         => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
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
            'message'  => 'تم إنشاء التبرع بنجاح',
            'donation' => $donation
        ], 201);
    }

    public function show($id)
    {
        $donation = Donation::findOrFail($id);
        return response()->json($donation);
    }

    // ✅ تغيير الحالة
    public function changeStatus(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);
        $donation->status = $request->status ?? 'مكتمل';
        $donation->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'donation' => $donation
        ]);
    }

    // ✅ حذف التبرع
    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->delete();

        return response()->json([
            'message' => 'Donation deleted successfully'
        ]);
    }
}

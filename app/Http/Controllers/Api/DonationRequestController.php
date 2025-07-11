<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonationRequest;
use Illuminate\Support\Facades\Storage;
use App\Notifications\DonationApprovedNotification;

class DonationRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = DonationRequest::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->get();

        return response()->json([
            'message' => 'Donation requests fetched successfully',
            'data'    => $requests
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|string',
            'quantity'    => 'nullable|integer',
            'condition'   => 'required|string',
            'description' => 'nullable|string',
            'location'    => 'nullable|string',
            'notes'       => 'nullable|string',
            'images.*'    => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $data = $request->except('images');
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'Pending';

        if ($request->hasFile('images')) {
            $paths = [];

            foreach ($request->file('images') as $file) {
                $filename = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/requests', $filename);
                $paths[] = 'requests/' . $filename;
            }

            $data['images'] = json_encode($paths);
        }

        $donationRequest = DonationRequest::create($data);

        return response()->json([
            'message' => 'Request submitted successfully',
            'data'    => $donationRequest
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Received',
        ]);

        $donation = DonationRequest::findOrFail($id);
        $donation->status = $request->status;
        $donation->save();

        if ($donation->status === 'Received' && $donation->user->fcm_token) {
            $donation->user->notify(new DonationApprovedNotification());
        }

        return response()->json([
            'message' => 'Status updated successfully',
            'data'    => $donation
        ]);
    }

    public function show($id)
    {
        $request = DonationRequest::find($id);

        if (!$request) {
            return response()->json([
                'message' => 'Donation request not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Donation request fetched successfully',
            'data' => $request
        ]);
    }

    public function destroy($id)
    {
        $request = DonationRequest::findOrFail($id);
        $request->delete();

        return response()->json([
            'message' => 'Donation request deleted successfully'
        ]);
    }
}

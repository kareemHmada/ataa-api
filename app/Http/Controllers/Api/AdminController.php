<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;

class AdminController extends Controller
{
    // Get all users
    public function getAllUsers()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Verify user
    public function verifyUser($id)
    {
        $user = User::findOrFail($id);
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'User verified successfully']);
    }

    // Delete user
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    // Get all donations (for admin)
    public function getAllDonations()
    {
        $donations = Donation::with('user')->latest()->get();
        return response()->json($donations);
    }

    // Change donation status
    public function changeDonationStatus($id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status === 'Pending') {
            $donation->status = 'Completed';
        } elseif ($donation->status === 'Completed') {
            $donation->status = 'Received';
        } else {
            $donation->status = 'Pending';
        }

        $donation->save();

        return response()->json(['message' => 'Donation status updated', 'donation' => $donation]);
    }

    // Confirm donation
    public function confirmDonation($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->status = 'Confirmed';
        $donation->save();

        return response()->json(['message' => 'Donation confirmed', 'donation' => $donation]);
    }

    // Delete donation
    public function deleteDonation($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->delete();

        return response()->json(['message' => 'Donation deleted successfully']);
    }
}

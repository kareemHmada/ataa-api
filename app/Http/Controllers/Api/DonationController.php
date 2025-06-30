<?php

namespace App\Http\Controllers\Api;

use App\Models\Donation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DonationController extends Controller
{
       public function index()
    {
        return response()->json(Donation::all());
    }

    public function store(Request $request)
    {
        $donation = Donation::create($request->all());
        return response()->json($donation, 201);
    }
}

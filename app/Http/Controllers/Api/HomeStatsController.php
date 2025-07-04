<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Donation;


class HomeStatsController extends Controller
{
       public function index()
    {
        return response()->json([
            'donors'        => User::where('role', 'donor')->count(),
            'receivers'     => User::where('role', 'receiver')->count(),
            'total_donated' => Donation::count(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Media;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'campaignsCount' => Campaign::count(),
            'mediaCount' => Media::count(),
            'latestCampaigns' => Campaign::with('advertiser')->latest()->limit(5)->get(),
        ]);
    }
}

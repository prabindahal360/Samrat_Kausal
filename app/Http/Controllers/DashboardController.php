<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalCampaigns = Campaign::count();
        $totalSent = CampaignLog::where('delivery_status', 'sent')->count();
        $totalFailed = CampaignLog::where('delivery_status', 'failed')->count();

        return view('dashboard', compact(
            'totalCustomers',
            'totalCampaigns',
            'totalSent',
            'totalFailed'
        ));
    }
}
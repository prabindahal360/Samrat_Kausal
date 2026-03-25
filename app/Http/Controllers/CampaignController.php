<?php

namespace App\Http\Controllers;

use App\Jobs\SendCampaignEmailJob;
use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(20);
        return view('campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('campaigns.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_category' => 'required|string|max:255',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        return view('campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'target_category' => 'required|string|max:255',
            'discount_percentage' => 'required|integer|min:0|max:100',
            'email_subject' => 'required|string|max:255',
            'email_body' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $campaign->update($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function send(Campaign $campaign)
    {
        $customers = Customer::where('customer_category', $campaign->target_category)
            ->whereNotNull('email')
            ->get();

        foreach ($customers as $customer) {
            SendCampaignEmailJob::dispatch($campaign->id, $customer->id);
        }

        $campaign->update([
            'sent_at' => now(),
        ]);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign sending started successfully.');
    }
}
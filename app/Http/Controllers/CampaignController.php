<?php

namespace App\Http\Controllers;

use App\Jobs\SendCampaignEmailJob;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\Customer;
use App\Services\EmailPersonalizationService;
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

        if ($customers->isEmpty()) {
            return redirect()->route('campaigns.index')
                ->with('error', 'No customers found for this campaign category.');
        }

        foreach ($customers as $customer) {
            \App\Models\CampaignLog::updateOrCreate(
                [
                    'campaign_id' => $campaign->id,
                    'customer_id_ref' => $customer->id,
                ],
                [
                    'email' => $customer->email,
                    'delivery_status' => 'pending',
                    'sent_at' => null,
                    'error_message' => null,
                ]
            );

            SendCampaignEmailJob::dispatch($campaign->id, $customer->id);
        }

        $campaign->update([
            'sent_at' => now(),
        ]);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign sending started successfully.');
    }

    public function logs(Campaign $campaign)
    {
        $logs = CampaignLog::with('customer')
            ->where('campaign_id', $campaign->id)
            ->latest()
            ->paginate(20);

        return view('campaigns.logs', compact('campaign', 'logs'));
    }

public function preview(Request $request, Campaign $campaign, EmailPersonalizationService $personalizationService)
{
    $customers = Customer::where('customer_category', $campaign->target_category)
        ->whereNotNull('email')
        ->orderBy('first_name')
        ->get();

    if ($customers->isEmpty()) {
        return redirect()->route('campaigns.index')
            ->with('error', 'No customers available for preview.');
    }

    $selectedCustomerId = $request->customer_id;

    $customer = $customers->firstWhere('id', $selectedCustomerId);

    if (!$customer) {
        $customer = $customers->first();
    }

    // ✅ LOAD PURCHASE HISTORY
    $customer->load(['invoiceItems.product']);

    $previewSubject = $personalizationService->generate(
        $campaign->email_subject,
        $customer,
        $campaign->discount_percentage
    );

    $previewBody = $personalizationService->generate(
        $campaign->email_body,
        $customer,
        $campaign->discount_percentage
    );

    $recommendedProducts = $customer->recommended_products ?? [];

    return view('campaigns.preview', compact(
        'campaign',
        'customers',
        'customer',
        'selectedCustomerId',
        'previewSubject',
        'previewBody',
        'recommendedProducts'
    ));
}
}

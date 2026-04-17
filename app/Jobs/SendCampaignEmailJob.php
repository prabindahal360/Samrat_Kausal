<?php

namespace App\Jobs;

use App\Mail\CampaignOfferMail;
use App\Models\Campaign;
use App\Models\CampaignLog;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $campaignId;
    public int $customerId;

    public function __construct(int $campaignId, int $customerId)
    {
        $this->campaignId = $campaignId;
        $this->customerId = $customerId;
    }

    public function handle(): void
    {
        $campaign = Campaign::find($this->campaignId);
        $customer = Customer::find($this->customerId);

        if (!$campaign || !$customer || empty($customer->email)) {
            return;
        }

        $log = CampaignLog::updateOrCreate(
            [
                'campaign_id' => $campaign->id,
                'customer_id_ref' => $customer->id,
            ],
            [
                'email' => $customer->email,
                'delivery_status' => 'pending',
                'error_message' => null,
            ]
        );

        try {
            Mail::to($customer->email)->send(new CampaignOfferMail($campaign, $customer));

            $log->update([
                'delivery_status' => 'sent',
                'sent_at' => now(),
                'error_message' => null,
            ]);

            $customer->update([
                'last_recommendation_sent_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'delivery_status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}
<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\Customer;
use App\Services\EmailPersonalizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public Campaign $campaign;
    public Customer $customer;
    public string $personalizedSubject;
    public string $personalizedBody;
    public array $recommendedProducts = [];

    public function __construct(Campaign $campaign, Customer $customer)
    {
        $this->campaign = $campaign;
        $this->customer = $customer;

        $personalizationService = app(EmailPersonalizationService::class);

        $this->personalizedSubject = $personalizationService->generate(
            $campaign->email_subject,
            $customer,
            $campaign->discount_percentage ?? null
        );

        $this->personalizedBody = $personalizationService->generate(
            $campaign->email_body,
            $customer,
            $campaign->discount_percentage ?? null
        );

        $this->recommendedProducts = $this->parseRecommendedProducts($customer->recommended_products);
    }

    public function build()
    {
        return $this->subject($this->personalizedSubject)
            ->view('emails.campaign-offer');
    }

    protected function parseRecommendedProducts($recommendedProducts): array
    {
        if (empty($recommendedProducts)) {
            return [];
        }

        if (is_array($recommendedProducts)) {
            return $recommendedProducts;
        }

        if (is_string($recommendedProducts)) {
            $decoded = json_decode($recommendedProducts, true);
            return is_array($decoded) ? $decoded : [];
        }

        return [];
    }
}
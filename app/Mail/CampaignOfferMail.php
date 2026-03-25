<?php

namespace App\Mail;

use App\Models\Campaign;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignOfferMail extends Mailable
{
    use Queueable, SerializesModels;

    public Campaign $campaign;
    public Customer $customer;

    public function __construct(Campaign $campaign, Customer $customer)
    {
        $this->campaign = $campaign;
        $this->customer = $customer;
    }

    public function build()
    {
        return $this->subject($this->campaign->email_subject)
            ->view('emails.campaign_offer');
    }
}
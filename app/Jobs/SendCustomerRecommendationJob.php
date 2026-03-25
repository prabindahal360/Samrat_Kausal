<?php

namespace App\Jobs;

use App\Mail\CustomerRecommendationMail;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCustomerRecommendationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $customerId;

    public function __construct(int $customerId)
    {
        $this->customerId = $customerId;
    }

    public function handle(): void
    {
        $customer = Customer::find($this->customerId);

        if (!$customer || empty($customer->email)) {
            return;
        }

        Mail::to($customer->email)
            ->send(new CustomerRecommendationMail($customer));
    }
}
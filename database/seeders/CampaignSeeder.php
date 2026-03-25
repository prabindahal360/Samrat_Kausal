<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {

        $campaigns = [
            [
                'name' => 'Low Value Retention Campaign',
                'target_category' => 'Low Value',
                'discount_percentage' => 2,
                'email_subject' => 'Special Offer Just for You!',
                'email_body' => "We noticed you haven't shopped much recently.\n\nEnjoy a small discount and explore our latest products!",
                'is_active' => true,
            ],
            [
                'name' => 'Medium Value Boost Campaign',
                'target_category' => 'Medium Value',
                'discount_percentage' => 5,
                'email_subject' => 'Enjoy 5% Off Your Next Purchase!',
                'email_body' => "Thank you for shopping with us.\n\nHere’s a 5% discount to make your next purchase even better!",
                'is_active' => true,
            ],
            [
                'name' => 'High Value Reward Campaign',
                'target_category' => 'High Value',
                'discount_percentage' => 10,
                'email_subject' => 'Exclusive 10% Discount for You!',
                'email_body' => "You are one of our valued customers.\n\nEnjoy 10% off on your next purchase as a token of appreciation.",
                'is_active' => true,
            ],
            [
                'name' => 'VIP Exclusive Campaign',
                'target_category' => 'VIP',
                'discount_percentage' => 15,
                'email_subject' => 'VIP Offer – 15% Off!',
                'email_body' => "As one of our VIP customers, you get exclusive benefits.\n\nEnjoy a 15% discount on your next order.",
                'is_active' => true,
            ],
        ];

        foreach ($campaigns as $campaign) {
            Campaign::create($campaign);
        }
    }
}
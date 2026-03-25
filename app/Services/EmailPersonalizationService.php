<?php
namespace App\Services;

use App\Models\Customer;

class EmailPersonalizationService
{
    public function generate(string $template, Customer $customer): string
    {
        $placeholders = [
            '{{name}}' => $customer->name ?? '',
            '{{email}}' => $customer->email ?? '',
            '{{country}}' => $customer->country ?? '',
            '{{membership_years}}' => $customer->membership_years ?? '',
            '{{num_purchases}}' => $customer->num_purchases ?? '',
            '{{avg_purchase_value}}' => $customer->avg_purchase_value ?? '',
            '{{annual_income}}' => $customer->annual_income ?? '',
            '{{spending_score}}' => $customer->spending_score ?? '',
            '{{feedback_text}}' => $customer->feedback_text ?? '',
            '{{last_purchase_date}}' => $customer->last_purchase_date ?? '',
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }
}
<?php

namespace App\Services;

use App\Models\Customer;

class EmailPersonalizationService
{
    public function generate(string $template, Customer $customer, ?int $campaignDiscount = null): string
    {
        $placeholders = [
            '{{first_name}}' => $customer->first_name ?? '',
            '{{last_name}}' => $customer->last_name ?? '',
            '{{full_name}}' => trim(($customer->first_name ?? '') . ' ' . ($customer->last_name ?? '')),
            '{{email}}' => $customer->email ?? '',
            '{{country}}' => $customer->country ?? '',
            '{{customer_id}}' => $customer->customer_id ?? '',
            '{{total_spend}}' => number_format((float) ($customer->total_spend ?? 0), 2),
            '{{customer_category}}' => $customer->customer_category ?? '',
            '{{discount_percentage}}' => (string) ($campaignDiscount ?? $customer->discount_percentage ?? 0),
            '{{last_recommendation_sent_at}}' => optional($customer->last_recommendation_sent_at)->format('Y-m-d H:i') ?? '',
            '{{recommended_products}}' => $this->formatRecommendedProducts($customer->recommended_products),
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $template);
    }

    protected function formatRecommendedProducts($recommendedProducts): string
    {
        if (empty($recommendedProducts)) {
            return 'No recommendations available';
        }

        if (is_string($recommendedProducts)) {
            $decoded = json_decode($recommendedProducts, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $recommendedProducts = $decoded;
            } else {
                return $recommendedProducts;
            }
        }

        if (is_array($recommendedProducts)) {
            return collect($recommendedProducts)
                ->map(function ($item) {
                    if (is_array($item)) {
                        return $item['name'] ?? ($item['stock_code'] ?? 'Product');
                    }

                    return (string) $item;
                })
                ->implode(', ');
        }

        return (string) $recommendedProducts;
    }
}
<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Product;

class CustomerAiService
{
    public function updateCustomerProfile(Customer $customer): Customer
    {
        $customer->loadMissing('invoiceItems.product');

        $totalSpend = $customer->invoiceItems->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $category = $this->categorizeCustomer($totalSpend);
        $discount = $this->getDiscountByCategory($category);
        $recommendations = $this->recommendProducts($customer);

        $customer->update([
            'total_spend' => $totalSpend,
            'customer_category' => $category,
            'discount_percentage' => $discount,
            'recommended_products' => $recommendations,
        ]);

        return $customer->fresh();
    }

    public function categorizeCustomer(float $totalSpend): string
    {
        if ($totalSpend < 100) {
            return 'Low Value';
        }

        if ($totalSpend < 200) {
            return 'Medium Value';
        }

        if ($totalSpend < 500) {
            return 'High Value';
        }

        return 'VIP';
    }

    public function getDiscountByCategory(string $category): int
    {
        return match ($category) {
            'Low Value' => 2,
            'Medium Value' => 5,
            'High Value' => 10,
            'VIP' => 15,
            default => 0,
        };
    }

    public function recommendProducts(Customer $customer): array
    {
        $customer->loadMissing('invoiceItems.product');

        $boughtCategories = $customer->invoiceItems
            ->pluck('product.category')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $boughtProductIds = $customer->invoiceItems
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $query = Product::query()
            ->where('is_active', true);

        if (!empty($boughtCategories)) {
            $query->whereIn('category', $boughtCategories);
        }

        if (!empty($boughtProductIds)) {
            $query->whereNotIn('id', $boughtProductIds);
        }

        return $query->orderBy('name')
            ->take(5)
            ->get(['id', 'stock_code', 'name', 'category', 'unit_price'])
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'stock_code' => $product->stock_code,
                    'name' => $product->name,
                    'category' => $product->category,
                    'unit_price' => (float) $product->unit_price,
                ];
            })
            ->toArray();
    }
}
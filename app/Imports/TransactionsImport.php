<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Services\CustomerAiService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $updatedCustomerIds = [];

        foreach ($rows as $row) {
            if (empty($row['customerid']) || empty($row['email'])) {
                continue;
            }

            $customer = Customer::updateOrCreate(
                ['customer_id' => trim((string) $row['customerid'])],
                [
                    'first_name' => $row['firstname'] ?? null,
                    'last_name'  => $row['lastname'] ?? null,
                    'email'      => $row['email'] ?? null,
                    'country'    => $row['country'] ?? null,
                ]
            );

            $product = Product::updateOrCreate(
                ['stock_code' => trim((string) ($row['stockcode'] ?? ''))],
                [
                    'name' => $row['description'] ?? null,
                    'description' => $row['description'] ?? null,
                    'unit_price' => (float) ($row['unitprice'] ?? 0),
                    'category' => $this->detectCategory($row['description'] ?? ''),
                    'is_active' => true,
                ]
            );

            InvoiceItem::create([
                'invoice_no'      => $row['invoiceno'] ?? null,
                'customer_ref_id' => $customer->id,
                'product_id'      => $product->id,
                'stock_code'      => $row['stockcode'] ?? null,
                'description'     => $row['description'] ?? null,
                'quantity'        => (int) ($row['quantity'] ?? 0),
                'invoice_date'    => !empty($row['invoicedate'])
                    ? Carbon::parse($row['invoicedate'])->format('Y-m-d H:i:s')
                    : null,
                'unit_price'      => (float) ($row['unitprice'] ?? 0),
            ]);

            $updatedCustomerIds[$customer->id] = $customer->id;
        }

        $aiService = app(CustomerAiService::class);

        foreach ($updatedCustomerIds as $customerId) {
            $customer = Customer::with(['invoiceItems.product'])->find($customerId);

            if ($customer) {
                $customer = $aiService->updateCustomerProfile($customer);

                if (in_array($customer->customer_category, ['Medium Value', 'High Value', 'VIP'])) {
                    \App\Jobs\SendCustomerRecommendationJob::dispatch($customer->id);
                }
            }
        }
    }

    private function detectCategory(string $description): ?string
    {
        $text = strtoupper(trim($description));

        if ($text === '') {
            return null;
        }

        if (str_contains($text, 'LUNCH') || str_contains($text, 'SNACK')) {
            return 'Lunch & Kitchen';
        }

        if (str_contains($text, 'HOLDER') || str_contains($text, 'LANTERN') || str_contains($text, 'HEART')) {
            return 'Home Decor';
        }

        if (str_contains($text, 'JIGSAW') || str_contains($text, 'DOLL') || str_contains($text, 'PLAYHOUSE')) {
            return 'Toys & Kids';
        }

        if (str_contains($text, 'ALARM CLOCK')) {
            return 'Clocks';
        }

        if (str_contains($text, 'HAND WARMER')) {
            return 'Seasonal';
        }

        if (str_contains($text, 'BOX')) {
            return 'Storage & Boxes';
        }

        return 'General';
    }
}
<?php

namespace App\Http\Controllers;

use App\Imports\CustomersImport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TransactionsImport;
use App\Jobs\SendCampaignEmailJob;
use App\Models\Campaign;
use App\Services\CustomerAiService;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        $campaigns = Campaign::where('is_active', true)->get();

        return view('customers.index', compact('customers', 'campaigns'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|max:255|unique:customers,customer_id',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'country' => 'nullable|string|max:255',
        ]);

        $customer = Customer::create($validated);

        app(\App\Services\CustomerAiService::class)->updateCustomerProfile($customer);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }


    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|max:255|unique:customers,customer_id,' . $customer->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'country' => 'nullable|string|max:255',
        ]);

        $customer->update($validated);

        app(\App\Services\CustomerAiService::class)->updateCustomerProfile($customer);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function showImportForm()
    {
        return view('customers.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new TransactionsImport, $request->file('file'));

        $aiService = app(\App\Services\CustomerAiService::class);

        \App\Models\Customer::with('invoiceItems.product')->chunk(100, function ($customers) use ($aiService) {
            foreach ($customers as $customer) {
                $aiService->updateCustomerProfile($customer);
            }
        });

        return redirect()->route('customers.index')
            ->with('success', 'Dataset imported successfully. Customer spend, category, discount, and recommendations were updated.');
    }

    public function downloadFormat()
    {
        $headers = [
            'InvoiceNo',
            'FirstName',
            'LastName',
            'Email',
            'StockCode',
            'Description',
            'Quantity',
            'InvoiceDate',
            'UnitPrice',
            'CustomerID',
            'Country',
        ];

        $sampleRow = [
            '536365',
            'Emily',
            'Martin',
            'emily.martin23162@icloud.com',
            '85123A',
            'WHITE HANGING HEART T-LIGHT HOLDER',
            '6',
            '12/1/2010 8:26',
            '2.55',
            '17850',
            'United Kingdom',
        ];

        $filename = 'dataset_format.csv';

        $callback = function () use ($headers, $sampleRow) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            fputcsv($file, $sampleRow);
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }



    public function highValueCustomers()
    {
        $customers = Customer::with('invoiceItems')->get()
            ->map(function ($customer) {
                $customer->total_spend = $customer->invoiceItems->sum(fn($i) => $i->quantity * $i->unit_price);
                return $customer;
            })
            ->sortByDesc('total_spend')
            ->take(10);

        return $customers;
    }

    public function recommendProducts($customerId)
    {
        $customer = Customer::with('invoiceItems')->findOrFail($customerId);

        $products = $customer->invoiceItems->pluck('description')->toArray();

        $recommendations = [];

        foreach ($products as $product) {

            if (str_contains($product, 'HEART') || str_contains($product, 'HOLDER')) {
                $recommendations[] = 'WHITE METAL LANTERN';
                $recommendations[] = 'GLASS STAR FROSTED HOLDER';
            }

            if (str_contains($product, 'LUNCH')) {
                $recommendations[] = 'SNACK BOX SET';
            }

            if (str_contains($product, 'PLAYHOUSE') || str_contains($product, 'DOLL')) {
                $recommendations[] = 'JIGSAW PUZZLES';
            }

            if (str_contains($product, 'BOX')) {
                $recommendations[] = 'STORAGE BOXES';
            }
        }

        return array_unique($recommendations);
    }

    public function sendBulkEmail(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array',
            'customer_ids.*' => 'exists:customers,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
        ]);

        $customers = Customer::whereIn('id', $request->customer_ids)->get();

        if ($customers->isEmpty()) {
            return redirect()->route('customers.index')
                ->with('error', 'No customers selected.');
        }

        foreach ($customers as $customer) {
            // If campaign manually selected, use it
            if (!empty($request->campaign_id)) {
                $campaign = Campaign::find($request->campaign_id);
            } else {
                // Auto pick by customer category
                $campaign = Campaign::where('target_category', $customer->customer_category)
                    ->where('is_active', true)
                    ->first();
            }

            if ($campaign && !empty($customer->email)) {
                SendCampaignEmailJob::dispatch($campaign->id, $customer->id);
            }
        }

        return redirect()->route('customers.index')
            ->with('success', 'Bulk email sending started for selected customers.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Services\CustomerAiService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'stock_code' => 'required|unique:products,stock_code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Product::create($validated);

        $aiService = app(CustomerAiService::class);

        Customer::with('invoiceItems.product')->chunk(100, function ($customers) use ($aiService) {
            foreach ($customers as $customer) {
                $aiService->updateCustomerProfile($customer);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully and customer recommendations refreshed.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'stock_code' => 'required|unique:products,stock_code,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit_price' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        $aiService = app(CustomerAiService::class);

        Customer::with('invoiceItems.product')->chunk(100, function ($customers) use ($aiService) {
            foreach ($customers as $customer) {
                $aiService->updateCustomerProfile($customer);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully and customer recommendations refreshed.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        $aiService = app(CustomerAiService::class);

        Customer::with('invoiceItems.product')->chunk(100, function ($customers) use ($aiService) {
            foreach ($customers as $customer) {
                $aiService->updateCustomerProfile($customer);
            }
        });

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully and customer recommendations refreshed.');
    }
}

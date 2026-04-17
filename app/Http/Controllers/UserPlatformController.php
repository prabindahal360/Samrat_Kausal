<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CustomerAiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserPlatformController extends Controller
{
public function dashboard()
{
    $user = auth()->user();

    $customer = $user->customer;

    if (!$customer) {
        $nameParts = preg_split('/\s+/', trim($user->name), 2);

        $customer = \App\Models\Customer::create([
            'user_id' => $user->id,
            'customer_id' => 'CUST-' . time() . rand(100, 999),
            'first_name' => $nameParts[0] ?? $user->name,
            'last_name' => $nameParts[1] ?? null,
            'email' => $user->email,
            'country' => null,
            'total_spend' => 0,
            'customer_category' => 'Low Value',
            'discount_percentage' => 2,
            'recommended_products' => [],
        ]);

        app(\App\Services\CustomerAiService::class)->updateCustomerProfile($customer);
    }

    $customer->load('invoiceItems.product');

    return view('users.dashboard', compact('customer'));
}

    public function products()
    {
        $products = Product::where('is_active', true)->latest()->paginate(12);
        $customer = auth()->user()->customer;

        return view('users.products', compact('products', 'customer'));
    }

    public function buyProduct(Request $request, \App\Models\Product $product, CustomerAiService $aiService)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $customer = auth()->user()->customer;

        if (!$customer) {
            return redirect()->back()->with('error', 'Customer profile not found.');
        }

        $customer->invoiceItems()->create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(10)),
            'product_id' => $product->id,
            'stock_code' => $product->stock_code,
            'description' => $product->description,
            'quantity' => $request->quantity,
            'invoice_date' => now(),
            'unit_price' => $product->unit_price,
        ]);

        $aiService->updateCustomerProfile($customer->fresh());

        return redirect()->route('user.dashboard')
            ->with('success', 'Product purchased successfully.');
    }

    public function profile()
    {
        $customer = auth()->user()->customer()->with('invoiceItems.product')->firstOrFail();

        return view('users.profile', compact('customer'));
    }
}

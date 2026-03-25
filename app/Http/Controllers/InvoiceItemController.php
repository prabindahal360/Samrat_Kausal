<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;

class InvoiceItemController extends Controller
{
    public function index()
    {
        $invoiceItems = InvoiceItem::with('customer')->latest()->paginate(20);
        return view('invoice_items.index', compact('invoiceItems'));
    }
}
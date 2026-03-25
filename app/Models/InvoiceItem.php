<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_no',
        'customer_ref_id',
        'product_id',
        'stock_code',
        'description',
        'quantity',
        'invoice_date',
        'unit_price',
    ];

    protected $casts = [
        'invoice_date' => 'datetime',
        'unit_price' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_ref_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
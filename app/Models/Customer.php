<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'email',
        'country',
        'total_spend',
        'customer_category',
        'discount_percentage',
        'recommended_products',
        'last_recommendation_sent_at',
    ];

    protected $casts = [
        'recommended_products' => 'array',
        'last_recommendation_sent_at' => 'datetime',
        'total_spend' => 'decimal:2',
    ];

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class, 'customer_ref_id');
    }

    public function getFullNameAttribute()
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

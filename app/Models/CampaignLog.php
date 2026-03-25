<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLog extends Model
{
    use HasFactory;

     protected $fillable = [
        'campaign_id',
        'customer_id_ref',
        'email',
        'delivery_status',
        'sent_at',
        'error_message',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id_ref');
    }
}

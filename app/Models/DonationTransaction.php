<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationTransaction extends Model
{
    protected $fillable = [
        'reference',
        'status',
        'paid_at',
        'amount',
        'currency',
        'customer_name',
        'customer_phone',
        'customer_email',
        'checkout_url',
        'payment_link_url',
        'external_reference',
        'webhook_event',
        'failure_reason',
        'raw_payload',
    ];

    protected $casts = [
        'raw_payload' => 'array',
        'amount' => 'integer',
        'paid_at' => 'datetime',
    ];
}

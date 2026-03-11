<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SnippeWebhookEvent extends Model
{
    protected $fillable = [
        'event_id',
        'type',
        'received_at',
    ];

    protected $casts = [
        'received_at' => 'datetime',
    ];
}

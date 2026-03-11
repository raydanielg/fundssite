<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraiserExpense extends Model
{
    protected $fillable = [
        'spent_at',
        'description',
        'amount',
        'currency',
        'receipt_path',
    ];

    protected $casts = [
        'spent_at' => 'date',
        'amount' => 'integer',
    ];
}

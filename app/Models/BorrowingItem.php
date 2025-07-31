<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BorrowingItem extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'item_id',
        'quantity'
    ];
}
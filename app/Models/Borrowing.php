<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'borrow_date',
        'return_date',
        'location_id',
        'approved_by',
        'approved_at',
        'penalty',
        'penalty_status',
         'penalty_proof',
    ];

    protected $casts = [
    'borrow_date' => 'datetime:Y-m-d',
    'return_date' => 'datetime:Y-m-d',
];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class, 'borrowing_items')->withPivot('quantity', 'return_photo', 'condition');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function returnApprover()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

}
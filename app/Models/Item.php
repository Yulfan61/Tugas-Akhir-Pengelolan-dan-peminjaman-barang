<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'brand',
        'model',
        'year_of_purchase',
        'price',
        'specifications',
        'condition',
        'location_id',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function borrowingItems()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function histories()
    {
        return $this->hasMany(ItemHistory::class);
    }

    public function damageReports()
    {
        return $this->hasMany(DamageReport::class);
    }

    public function reports()
    {
        return $this->hasMany(DamageReport::class);
    }

    public function borrowings()
    {
        return $this->belongsToMany(Borrowing::class, 'borrowing_items')
            ->withPivot(['quantity', 'return_photo'])
            ->withTimestamps();
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'order_id',
        'status',
        'total',
        'currency',
        'customer_name',
        'order_date',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

     public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}

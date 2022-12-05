<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'name',
        'brand',
        'code',
        'description',
        'currency',
        'unit',
        'tax',
        'quantity',
        'price_unit',
        'discount_currency',
        'discount_price',
        'total_price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

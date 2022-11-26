<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'order_at',
        'code',
        'year',
        'number',
        'status',
        'currency',
        'total_price',
        'delivery_id',
        'delivery_method',
        'delivery_note',
        'trasport_method',
        'trasport_note',
        'payment_method',
        'payment_note',
        'notes',
    ];
}

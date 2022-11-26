<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'category' => 'array',
    ];

    protected $fillable = [
        'supplier_id',
        'brand',
        'name',
        'description',
        'code',
        'tax',
        'unit',
        'currency',
        'price',
        'category',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

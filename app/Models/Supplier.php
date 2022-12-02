<?php

namespace App\Models;

use App\Models\Warehouse\Order;
use App\Models\Warehouse\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $casts = [
        'category' => 'array',
    ];

    protected $fillable = [
        'name',
        'logo',
        'fiscal_code',
        'vat',
        'pec',

        'invoice_code',

        'code_acronym',
        'code_accounting',
        'category',
        'note',

        'default_tax_rate',
        'default_currency',
        'default_payment',

        'is_activated',
    ];

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    public function banks()
    {
        return $this->morphMany(Bank::class, 'bankable');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

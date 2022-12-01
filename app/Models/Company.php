<?php

namespace App\Models;

use App\Models\Warehouse\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
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

        'page_header',
        'page_footer',
        'page_warehouse_terms',
        'page_warehouse_info',

        'is_activated',
    ];
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

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
}

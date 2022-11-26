<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $casts = [
        'category' => 'array',
    ];

    protected $fillable = [
        'name',
        'lastname',
        'logo',
        'gender',
        'date_birth',
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
        'bank_id',

        'is_person',
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
}

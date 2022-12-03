<?php

namespace App\Models;

use App\Models\Warehouse\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_name',
        'name',
        'address',
        'street_number',
        'zip',
        'city',
        'province',
        'state',
    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}

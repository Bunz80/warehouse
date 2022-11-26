<?php

namespace App\Models;

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
}

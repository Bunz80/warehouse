<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_name',
        'name',
        'address',
    ];

    public function contactable()
    {
        return $this->morphTo();
    }
}

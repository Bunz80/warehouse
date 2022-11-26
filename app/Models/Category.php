<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_name',
        'name',
        'icon',
        'color',
        'order',
        'is_default',
        'is_activated',
    ];
}

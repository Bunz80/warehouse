<?php

namespace App\Models;

use App\Models\Contact;
use App\Models\Warehouse\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}

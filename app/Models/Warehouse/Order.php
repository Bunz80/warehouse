<?php

namespace App\Models\Warehouse;

// use Heloufir\FilamentWorkflowManager\Core\InteractsWithWorkflows;
// use Heloufir\FilamentWorkflowManager\Core\HasWorkflow;
use App\Models\Company;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model // implements HasWorkflow
{
    use HasFactory;
    // use InteractsWithWorkflows;

    protected $fillable = [
        'company_id',
        'supplier_id',
        'order_at',
        'code',
        'year',
        'number',

        'tax',
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
        'report',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

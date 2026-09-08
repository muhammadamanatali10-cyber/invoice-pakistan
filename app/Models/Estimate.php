<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'estimate_number',
        'ref_number',
        'estimate_date',
        'due_date',
        'status',
        'sub_total',
        'discount',
        'discount_type',
        'tax',
        'total_amount',
        'notes',
        'template',
    ];

    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    
    public function items()
    {
        return $this->hasMany(EstimateItem::class);
    }
}
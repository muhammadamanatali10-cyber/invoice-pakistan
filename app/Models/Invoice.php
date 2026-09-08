<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'estimate_id',
        'invoice_number',
        'ref_number',
        'invoice_date',
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

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
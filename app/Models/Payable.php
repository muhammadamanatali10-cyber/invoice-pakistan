<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'date',
        'status',
        'paid_status',
        'invoice_number',
        'amount_due',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
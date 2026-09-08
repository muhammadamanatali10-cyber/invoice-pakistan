<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_id',
        'payment_number',
        'payment_date',
        'payment_mode',
        'amount',
        'notes',
    ];

   
    protected static function booted()
    {
        static::creating(function ($payment) {
            if (empty($payment->payment_number)) {
                $latestPayment = self::latest('id')->first();
                
                if ($latestPayment) {
                    $lastNumber = intval(str_replace('PAY - ', '', $latestPayment->payment_number));
                    $payment->payment_number = 'PAY - ' . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
                } else {
                    $payment->payment_number = 'PAY - 000001';
                }
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
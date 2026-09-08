<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'display_name',
        'email',
        'phone',
        'status',
    ];

    public function payables()
    {
        return $this->hasMany(Payable::class);
    }
}
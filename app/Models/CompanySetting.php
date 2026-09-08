<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_logo',
        'company_name',
        'phone',
        'country',
        'state',
        'city',
        'zip',
        'address_line1',
        'address_line2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
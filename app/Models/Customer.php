<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    public function getNameAttribute($value)
    {
        
        if (!empty($value)) {
            return $value;
        }

       
        if (!empty($this->attributes['first_name'])) {
            return trim($this->attributes['first_name'] . ' ' . ($this->attributes['last_name'] ?? ''));
        }

       
        if (!empty($this->attributes['display_name'])) {
            return $this->attributes['display_name'];
        }

        
        if (!empty($this->attributes['customer_name'])) {
            return $this->attributes['customer_name'];
        }

       
        if (!empty($this->attributes['company_name'])) {
            return $this->attributes['company_name'];
        }

        return 'Customer #' . $this->id;
    }
}
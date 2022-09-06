<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'delivery_name',
        'delivery_zip_code',
        'delivery_city',
        'delivery_street',
        'billing_name',
        'billing_zip_code',
        'billing_city',
        'billing_street',
        'order_id'
    ];
}

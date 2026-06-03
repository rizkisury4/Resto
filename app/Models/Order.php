<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'quantity',
        'customer_name',
        'notes',
        'payment_method',
        'status',
        'total_price',
    ];
}

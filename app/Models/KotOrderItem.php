<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotOrderItem extends Model
{
    protected $fillable = ['kot_order_id', 'product_id', 'product_name', 'quantity', 'price', 'total', ];
}

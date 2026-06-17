<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KotOrder extends Model
{
    protected $fillable = [ 'session_id', 'seat_id', 'area_id', 'product_id', 'product_name', 'price', 'quantity', 'order_no', 'subtotal', 'cgst', 'sgst', 'total', 'status' ];

    public function items() {
        return $this->hasMany(KotOrderItem::class);
    }

    public function kot() {
        return $this->hasMany(KotOrder::class);
    }
}

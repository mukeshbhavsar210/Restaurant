<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'product_name', 'quantity', 'price', 'total', ];

    public function orders(){
        return $this->belongsTo(Order::class, 'order_id', );
    }

    public function assigned_seat(){
        return $this->belongsTo(Seat::class, 'seat_id', );
    }

    public function seat(){
        return $this->belongsTo(Seat::class);
    }

     public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function subcategory() {
        return $this->belongsTo(SubCategory::class);
    }
}
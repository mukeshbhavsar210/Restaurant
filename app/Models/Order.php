<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $fillable = ['order_type', 'session_id', 'seat_id', 'dinein_time', 'customer_name', 'customer_phone', 'customer_email',
        'notes', 'ready_time', 'delivery_address', 'total_amount', 'payment', 'status'
    ];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function product_images(){
        return $this->hasMany(ProductImage::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // public function items(){
    //     return $this->belongsTo(Order::class);
    // }

    public function seat() {
        return $this->belongsTo(Seat::class);
    }

    // public function seat(){
    //     return $this->belongsTo(Seat::class, 'seat_id');
    // }   
    
}
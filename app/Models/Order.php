<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $fillable = ['order_type', 'session_id', 'seat_id', 'area_id', 'branch', 'table', 'name', 'phone', 'email',
        'address', 'notes', 'total', 'payment_method', 'payment_status', 'razorpay_order_id', 'razorpay_payment_id', 'status'
    ];

    public function area() {
        return $this->belongsTo(Area::class);
    }

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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model {
    use HasFactory;   

    protected $fillable = [ 'table', 'type_id', 'area_id', 'table_order', 'status', 'capacity' ];

    public function seat(){
        return $this->hasMany(Seat::class, 'area_id');
    }
    
    // public function seat(){
    //     //return $this->belongsTo(Order::class, 'id');
    //     return $this->belongsTo(OrderItem::class);
    // }

    // public function area(){
    //     return $this->belongsTo(Area::class, 'id');
    // }

    public function type() {
        return $this->belongsTo(TableType::class, 'type_id');
    }    

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function order(){
        return $this->belongsTo(Order::class, 'status');
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    public function kotOrders() {
        return $this->hasMany(KotOrder::class, 'seat_id');
    }

    public function latestKotOrder() {
        return $this->hasOne(KotOrder::class, 'seat_id')->latestOfMany();
    }

    public function assigned_seat(){
        return $this->hasMany(Order::class);
    }


    // public function assigned_seat(){
    //     return $this->belongsTo(Order::class, 'seat_id');
    // }
    
    // public function assigned_seat(){
    //     return $this->belongsTo(Order::class, 'seat_id', );
    // }

    

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function menu(){
        return $this->belongsTo(Menu::class);
    }
}

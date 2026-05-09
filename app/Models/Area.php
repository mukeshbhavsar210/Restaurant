<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public function seat(){
        return $this->hasMany(Seat::class);
    }    

    public function categories(){
        return $this->hasMany(Category::class);
    }

    public function menu(){
        return $this->hasMany(Menu::class);
    }

    public function seating(){
        return $this->hasMany(Seat::class);
    }

}

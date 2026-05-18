<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    use HasFactory;

    // public function category_menu(){
    //     return $this->hasMany(Menu::class);
    // }

    public function menus() {
        return $this->belongsToMany(Menu::class, 'category_menu');
    }

    // public function menus() {
    //     return $this->belongsToMany(
    //         Menu::class,
    //         'category_menu',
    //         'category_id',
    //         'menu_id'
    //     );
    // }

    // public function products(){
    //     return $this->hasMany(Product::class);
    // }  

    public function products() {
        return $this->hasMany(Product::class, 'category_id');
    }
}

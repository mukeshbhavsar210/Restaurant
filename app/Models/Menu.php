<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name','slug','image',];

    // public function category(){
    //     return $this->belongsTo(Category::class);
    // }

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_menu');
    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    // public function categories() {
    //     return $this->belongsToMany(
    //         Category::class,
    //         'category_menu',
    //         'menu_id',
    //         'category_id'
    //     );
    // }
}
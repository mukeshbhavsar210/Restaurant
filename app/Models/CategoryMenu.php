<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryMenu extends Model {
    protected $table = 'category_menu';

    protected $fillable = [
        'category_id',
        'menu_id',
    ];
}

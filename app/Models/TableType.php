<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    protected $fillable = [
        'name',
    ];

    public function seats() {
        return $this->hasMany(Seat::class, 'type_id');
    }
}
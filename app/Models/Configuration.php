<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
   use HasFactory;
   protected $fillable = ['name','logo','image','email','phone','address','theme','taxes','percentages','plan'];

   protected $primaryKey = null;
   public $incrementing = false;

   public function seat(){
      return $this->hasMany(Seat::class);
  } 
}

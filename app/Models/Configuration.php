<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
   use HasFactory;
   protected $fillable = ['name','logo','email','phone','address','business_types','primary_color','secondary_color', 'payment_key_id', 'payment_key_secret'];

   protected $primaryKey = null;
   public $incrementing = false;

   public function seat(){
      return $this->hasMany(Seat::class);
  } 
}

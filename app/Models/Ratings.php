<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Ratings extends Model
{
    
	
	
  protected $table = 'product_ratings';
  public function Product(){
      return $this->belongsTo(Product::class);
   }
  
}

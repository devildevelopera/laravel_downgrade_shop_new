<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Category extends Model
{
    
	/* category */
	
		
	
  public static function getsinglecatData($item_cat_id)
  {

    $value=DB::table('category')->where('cat_id','=',$item_cat_id)->first(); 
    return $value;
	
  }	
	
	
  public static function slugcategoryData($slug)
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->where('category_slug','=',$slug)->first(); 
    return $value;
	
  }	
	
	
  public static function mainmenuCategoryData($take,$order)
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->take($take)->orderBy('category_name', $order)->get(); 
    return $value;
	
  }	
  
  
	
  
  public static function getcategoryData()
  {

    $value=DB::table('category')->where('drop_status','=','no')->orderBy('cat_id', 'desc')->get(); 
    return $value;
	
  }
  
  public static function totalcategoryCount()
  {

    $get=DB::table('category')->where('drop_status','=','no')->orderBy('cat_id', 'desc')->get(); 
    $value = $get->count();
	return $value;
	
  }
  
  
  
  public static function recentcategoryData($take)
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->orderBy('cat_id', 'desc')->take($take)->get(); 
    return $value;
	
  }
  
  
  public static function footcategoryData($take)
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->orderBy('display_order', 'asc')->take($take)->get(); 
    return $value;
	
  }
  
  
  public static function quickbookData()
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->orderBy('display_order', 'asc')->get(); 
    return $value;
	
  }
  
  
  public static function eventCategoryData()
  {

    $value=DB::table('category')->join('events','events.event_cat_id','category.cat_id')->where('category.drop_status','=','no')->where('category.category_status','=',1)->where('events.event_status','=',1)->orderBy('category.display_order', 'asc')->groupBy('category.cat_id')->get(); 
    return $value;
	
  }
  
  
  public static function getgroupeventData()
  {

    $value=DB::table('events')->where('event_status','=',1)->orderBy('event_id', 'desc')->get()->groupBy('event_cat_id'); 
    return $value;
	
  }	
  
  
  
  public static function getgroupcauseData()
  {

    $value=DB::table('causes')->where('cause_status','=',1)->orderBy('cause_id', 'desc')->get()->groupBy('cat_id'); 
    return $value;
	
  }	
  
  
  
  public static function categorydisplayOrder()
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=',1)->orderBy('display_order', 'asc')->get(); 
    return $value;
	
  }
  
  
  
  public static function insertcategoryData($data){
   
      DB::table('category')->insert($data);
     
 
    }
  
  public static function deleteCategorydata($cat_id,$data){
    
	
		
	DB::table('category')
      ->where('cat_id', $cat_id)
      ->update($data);
	
  }
  
  
  public static function dropCategoryimage($cat_id)
  {
		 $image = DB::table('category')->where('cat_id', $cat_id)->first();
			$file= $image->category_image;
			$filename = public_path().'/storage/category/'.$file;
			File::delete($filename);
  }
  
  
  
  public static function editcategoryData($cat_id){
    $value = DB::table('category')
      ->where('cat_id', $cat_id)
      ->first();
	return $value;
  }
  
  
  public static function updatecategoryData($cat_id,$data){
    DB::table('category')
      ->where('cat_id', $cat_id)
      ->update($data);
  }
  
  
  public static function allcategoryData()
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=','1')->orderBy('cat_id', 'desc')->get(); 
    return $value;
	
  }
  
  
  public static function menucategoryData()
  {

    $value=DB::table('category')->where('drop_status','=','no')->where('category_status','=','1')->orderBy('cat_id', 'asc')->get(); 
    return $value;
	
  }
  
  /* category */
  
  
	
  
  
  
}

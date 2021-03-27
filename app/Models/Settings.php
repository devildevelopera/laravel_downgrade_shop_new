<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Settings extends Model
{
    
	
		
	/* settings */
	

	  public static function editGeneral($sid){
		$value = DB::table('settings')
		  ->where('sid', 1)
		  ->first();
		return $value;
	  }
	  
	  public static function updategeneralData($sid,$data){
		DB::table('settings')
		  ->where('sid', 1)
		  ->update($data);
	  }
	  
	  public static function dropWatermark($sid)
	  {
		 $image = DB::table('settings')->where('sid', $sid)->first();
			$file= $image->site_watermark;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
  	  
	  public static function dropFavicon($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_favicon;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  
	  public static function dropLogo($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_logo;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  
	  public static function dropBanner($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_banner;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  
	  public static function dropFoot($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_footer_logo;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  public static function dropLoader($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_loader_image;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  
	   public static function dropAboutbanner($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_about_image;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  
	  public static function dropPaymentbanner($sid)
	  {
		 $image = DB::table('settings')->where('sid', 1)->first();
			$file= $image->site_footer_payment;
			$filename = public_path().'/storage/settings/'.$file;
			File::delete($filename);
	  }
	  
	  
	 public static function updatemailData($sid,$data){
    DB::table('settings')
      ->where('sid', $sid)
      ->update($data);
     }
  
	/* settings */
	
	
	
  
  
  /* all settings */
  
  public static function allSettings(){
    $value = DB::table('settings')
      ->where('sid', 1)
      ->first();
	return $value;
  }
  
  
  /* all settings */
  
  
  /* country */
  
  public static function getcountryData()
	  {
	
		$value=DB::table('country')->orderBy('country_name', 'asc')->get(); 
		return $value;
		
	  }
	  
	 public static function savecountryData($data){
   
      DB::table('country')->insert($data);
     
 
    }
	
	
	public static function deleteCountrydata($cid){
    
	DB::table('country')->where('country_id', '=', $cid)->delete();	
	
	
  }
	
	
  public static function editCountry($cid){
    $value = DB::table('country')
      ->where('country_id', $cid)
      ->first();
	return $value;
  }	
  
  
  public static function updatecountryData($cid,$data){
    DB::table('country')
      ->where('country_id', $cid)
      ->update($data);
  }
  
  
  public static function allCountry(){
    $value = DB::table('country')
      ->orderBy('country_name', 'asc')
      ->get();
	return $value;
  }
	 
	/* country */  
  
  
  
  
}

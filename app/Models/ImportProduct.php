<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Auth;
use DownGrade\Models\Import;
use DownGrade\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportProduct implements ToModel
{

  
   
	
   public function model(array $row)
    {
	     
	    
           $data = Product::findProduct($row[2]);
           if (empty($data)) {
          
					  return new Import([
					   'user_id'    => $row[1], 
					   'product_token' => $row[2],
					   'product_name' => $row[3],
					   'product_slug' => $row[4],
					   'product_category' => $row[5],
					   'product_short_desc' => $row[6],
					   'product_desc' => $row[7],
					   'regular_price' => $row[8],
					   'extended_price' => $row[9],
					   'product_image' => $row[10],
					   'product_video_url' => $row[11],
					   'product_demo_url' => $row[12],
					   'product_allow_seo' => $row[13],
					   'product_seo_keyword' => $row[14],
					   'product_seo_desc' => $row[15],
					   'product_tags' => $row[16],
					   'product_flash_sale' => $row[17],
					   'product_free' => $row[18],
					   'download_count' => $row[19],
					   'product_views' => $row[20],
					   'product_liked' => $row[21],
					   'product_sold' => $row[22],
					   'product_featured' => $row[23],
					   'product_file' => $row[24],
					   'package_includes' => $row[25],
					   'compatible_browsers' => $row[26],
					   'future_update' => $row[27],
					   'item_support' => $row[28],
					   'product_date' => $row[29],
					   'product_update' => $row[30],
					   'product_status' => $row[31],
					   'product_drop_status' => $row[32],
					]);
		  
		  
              } 
     
	    
	
        
    }
   
   
  
  
}

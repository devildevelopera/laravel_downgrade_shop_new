<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Auth;

class Import extends Model
{

   protected $table = 'product';
   public $timestamps = false;
   
   protected $fillable = [
            'product_id',
            'user_id',
            'product_token',
            'product_name',
            'product_slug',
			'product_category',
			'product_short_desc',
			'product_desc',
			'regular_price',
			'extended_price',
			'product_image',
			'product_video_url',
			'product_demo_url',
			'product_allow_seo',
			'product_seo_keyword',
			'product_seo_desc',
			'product_tags',
			'product_flash_sale',
			'product_free',
			'download_count',
			'product_views',
			'product_liked',
			'product_sold',
			'product_featured',
			'product_file',
			'package_includes',
			'compatible_browsers',
			'future_update',
			'item_support',
			'product_date',
			'product_update',
			'product_status',
			'product_drop_status'
      
    ];
   
   
  
  
  
}

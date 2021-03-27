<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class ReplyComment extends Model
{
    
	
	
	protected $table = 'product_comment_reply';
	
	
     public function Comment()
	 {
      return $this->belongsTo(Comment::class);
     }
    
  
}

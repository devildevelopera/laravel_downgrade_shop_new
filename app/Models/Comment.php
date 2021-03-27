<?php

namespace DownGrade\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Comment extends Model
{
    
	
	
	protected $table = 'product_comments';
	
	
	public function ReplyComment()
    {
        return $this->hasMany(ReplyComment::class, 'comm_id', 'comm_id')->leftjoin('users', 'users.id', '=', 'product_comment_reply.comm_user_id');
    }
	
  
    
  
}

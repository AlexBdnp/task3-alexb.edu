<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Comment extends Model
{
    use HasFactory;

    public $timestamps = false;
    
    protected $guarded = [];

    public static function store()
    {
        
        $comment = new Comment();
        $comment->post_id  = $_POST['post_id'];
        $comment->content  = $_POST['content'];
        $comment->user_name = $_POST['user_name'];
        $comment->created_at = date("Y/m/d H:i:s");
        $comment->save();
        return $comment;
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}

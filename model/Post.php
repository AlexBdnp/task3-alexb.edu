<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;


class Post extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];

    public static function store()
    {        
        $post = new Post();
        $post->content = $_POST['content'];
        $post->user_name = $_POST['user_name'];
        $post->created_at = date("Y/m/d H:i:s");
        $post->save();
        return $post;
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function addRate($rate)
    {
        // $this->rates_total
    }
}

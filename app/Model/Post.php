<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function category(){
        return $this->belongsTo(PostCategory::class,"post_category_id");
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}

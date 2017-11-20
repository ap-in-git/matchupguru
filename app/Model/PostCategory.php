<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    public function post(){
        $this->hasMany(Post::class);
    }
}

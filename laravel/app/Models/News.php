<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['author_id', 'title', 'tag', 'content', 'image'];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}

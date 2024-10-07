<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image',
        'user_id',
        'published_at', 
        'featured',
        'education',
        'skills',
        'resume',
        'additional_links',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}



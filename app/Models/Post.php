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
        'location',
        'position',
        'published_at', 
        'featured',
        'education',
        'salary',
        'skills',
        'resume',
        'additional_links',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'post_user_applications')
                ->withPivot('cv_path', 'recruited', 'declined')
                ->withTimestamps();
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

}



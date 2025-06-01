<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Employer extends Authenticatable
{
    use HasFactory, CrudTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, 'employer_id');
    }
}

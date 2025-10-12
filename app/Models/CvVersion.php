<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'template',
        'language',
        'is_anonymized',
        'sha256_hash',
        'version_number',
        'notes',
        'data',
    ];

    protected $casts = [
        'is_anonymized' => 'boolean',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'owner',
        'repo',
        'subtitle',
        'description',
        'github_url',
        'live_url',
        'purpose',
        'type',
        'date_started',
        'language',
        'commit_count',
        'challenges',
        'features',
        'key_takeaways',
        'stack',
        'gallery'
    ];

    protected $casts = [
        'challenges' => 'array',
        'key_takeaways' => 'array',
        'stack' => 'array',
        'features' => 'array',
        'gallery' => 'array',
        'date_started' => 'datetime'
    ];
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

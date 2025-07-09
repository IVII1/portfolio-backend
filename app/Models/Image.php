<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'project_id',
        'image_url',
        'caption',
    ];

    public function projects()
    {
        return $this->belongsTo(Project::class);
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CalendarEntry extends Model

{

    use HasFactory;
    protected $fillable = [
        'date_published',
        'title',
        'content',
        'slug',
        'highlighted',
        'type'
        /* 'references' */
    ];
   /*  protected $casts = [
        'references' => 'array'
    ]; */

    public function categories(): BelongsToMany{
        return $this->belongsToMany(Category::class);
    }
    public function references(){
        return $this->belongsToMany(Reference::class);
    }


}

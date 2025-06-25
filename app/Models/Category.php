<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];

    public function entries()
    {
        return $this->hasMany(CalendarEntry::class);
    }
    public function categories(){
        return $this->hasMany(Reference::class);
    }
}

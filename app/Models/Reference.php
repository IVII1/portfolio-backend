<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    protected $fillable = [
        'source_name',
        'source_link',
        'count'
    ];
   
public function entries(){
    return $this->belongsToMany(CalendarEntry::class);
}
public function categories(){
    return $this->belongsToMany(Category::class);
}


}

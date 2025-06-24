<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEntryReference extends Model
{
    protected $fillable = [
        'calendar_entry_id',
        'reference_id',
    ];
}

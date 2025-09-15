<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Devotion extends Model
{
    protected $table = 'devotions';

    protected $fillable = [
        'favorite',
        'personal_reflection',
        'prayer_request',
        'application_notes',
        'study_date',
        'scripture_id',
        'mood',
    ];

    public function scripture()
    {
        return $this->belongsTo(Scripture::class);
    }
}

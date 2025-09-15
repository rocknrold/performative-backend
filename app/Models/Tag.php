<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'scripture_tags';

    protected $fillable = [
        'scripture_id',
        'tag',
    ];

    public function scriptures()
    {
        return $this->hasMany(Scripture::class);
    }
}

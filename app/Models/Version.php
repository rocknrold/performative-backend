<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'book_versions';

    protected $fillable = [
        'abbreviation',
        'name',
    ];

    public function scriptures()
    {
        return $this->hasMany(Scripture::class);
    }
}

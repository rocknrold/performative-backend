<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'name',
        'abbreviation',
        'seq',
    ];

    public function scriptures()
    {
        return $this->hasMany(Scripture::class);
    }
}

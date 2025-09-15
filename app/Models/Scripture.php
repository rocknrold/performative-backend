<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scripture extends Model
{
    protected $table = 'scriptures';

    protected $fillable = [
        'book_id',
        'book_version_id',
        'chapter',
        'verse',
        'text',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function version()
    {
        return $this->belongsTo(Version::class, 'book_version_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function devotion()
    {
        return $this->belongsTo(Devotion::class, 'id', 'scripture_id');
    }
}

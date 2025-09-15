<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Book;
use App\Models\Version;
use App\Models\Devotion;
use App\Models\Scripture;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScripturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Book::create([
            'name' => 'Genesis',
            'abbreviation' => 'Gen',
            'seq' => 1,
        ]);

        Version::create([
            'name' => 'King James Version',
            'abbreviation' => 'KJV'
        ]);

        Scripture::create([
            'book_id' => 1,
            'book_version_id' => 1,
            'chapter' => 1,
            'verse' => 1,
            'text' => 'In the beginning God created the heavens and the earth.',
        ]);

        Tag::create([
            'scripture_id' => 1,
            'tag' => 'love',
        ]);

        Devotion::create([
            'scripture_id' => 1,
            'personal_reflection' => 'I love God',
            'prayer_request' => 'I pray for God',
            'application_notes' => 'I apply God',
            'mood' => 'happy',
            'favorite' => true,
            'study_date' => now(),
        ]);
    }
}

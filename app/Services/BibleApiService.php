<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BibleApiService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function requestBible(string $url)
    {
        $response = Http::get(config('custom.bible_base_url') . '/' . $url);
        return $response->json();
    }

    public function getBooks()
    {
        return Cache::rememberForever('bible_books', function () {
            return  $this->requestBible(config('custom.bible_default_translation_code') . '/books.json')['books'];
        });
    }
}

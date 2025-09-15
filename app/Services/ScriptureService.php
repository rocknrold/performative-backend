<?php

namespace App\Services;

use App\Models\Tag;
use App\Models\Book;
use App\Models\Version;
use App\Models\Devotion;
use App\Models\Scripture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\GetScriptureResource;

class ScriptureService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function getScriptures(Request $request)
    {
        DB::enableQueryLog();

        $scriptures = Scripture::query();

        $scriptures->with([
            'book:id,name',
            'version:id,abbreviation',
            'tags:id,scripture_id,tag',
            'devotion'
        ]);

        /** Add query filters: search=te&book=John&version=NIV&tags=love&field=createdAt&direction=desc */

        if ($request->has('search') && $request->search != '') {
            $scriptures->where('text', 'like', '%' . $request->search . '%');
        }

        if ($request->has('book') && $request->book != '') {
            $scriptures->whereHas('book', function ($query) use ($request) {
                $query->where('name', $request->book);
            });
        }

        if ($request->has('version') && $request->version != '') {
            $scriptures->whereHas('version', function ($query) use ($request) {
                $query->where('abbreviation', $request->version);
            });
        }

        if ($request->has('tags') && $request->tags != '') {
            $scriptures->orWhereHas('tags', function ($query) use ($request) {
                $query->whereIn('tag', explode(',', $request->tags ?? "") ?? []);
            });
        }

        if ($request->has('field') && $request->has('direction')) {
            $sortableFields = [
                'createdAt' => 'created_at',
                'updatedAt' => 'updated_at',
                'studyDate' => 'created_at',
            ];

            $field = $sortableFields[$request->field] ?? $request->field;
            $direction = strtolower($request->direction) === 'asc' ? 'asc' : 'desc';
            $scriptures = $scriptures->orderBy($field, $direction);
            // $scriptures = $scriptures->whereHas('devotion', function ($query) use ($request, $field) {
            //     $direction = strtolower($request->direction) === 'asc' ? 'asc' : 'desc';
            //     $query->orderBy($field, $direction);
            // });
        }

        // dd($scriptures->simplePaginate($request->limit ?? 10), DB::getQueryLog());

        return GetScriptureResource::collection(
            $scriptures->simplePaginate($request->limit ?? 10)
        );
    }

    public function createScripture(Request $request)
    {
        //{
        //     "book": "John",
        //     "chapter": 1,
        //     "verse": 1,
        //     "text": "In the beginning was the Word, and the Word was with God, and the Word was God",
        //     "version": "NIV",
        //     "tags": [
        //         "salvation",
        //         "love"
        //     ],
        //     "notes": "optional note",
        //     "personalReflection": "God is good",
        //     "prayerRequest": "I love you Jesus",
        //     "applicationNotes": "Love Jesus everyday",
        //     "mood": "inspired",
        //     "isFavorite": true
        // }

        $request->validate([
            'book' => 'required',
            'chapter' => 'required',
            'verse' => 'required',
            'text' => 'required',
            'version' => 'required',
            'tags' => 'nullable',
            'notes' => 'nullable',
            'personalReflection' => 'nullable',
            'prayerRequest' => 'nullable',
            'applicationNotes' => 'nullable',
            'mood' => 'nullable',
            'isFavorite' => 'nullable',
        ]);

        $createdId = null;
        DB::transaction(function () use ($request, &$createdId) {
            $bibleApiService = new BibleApiService();

            $book = Book::where('name', $request->book)->first();
            if (!$book) {
                $book = collect($bibleApiService->getBooks())->first(function ($q) use ($request) {
                    if (str_contains($q['name'], $request->book)) {
                        return $q;
                    }
                });

                if (!$book) {
                    DB::rollBack();
                    throw new \Exception("No book found.");
                }

                $book = Book::create([
                    'name' => $book['name'],
                    'abbreviation' => $book['id'],
                    'seq' => $book['order'],
                ]);
            }

            $version = Version::where('abbreviation', $request->version)->first();
            if (!$version) {
                $version = Version::create([
                    'abbreviation' => $request->version,
                    'name' => $request->version,
                ]);
            }

            $scripture = Scripture::create([
                'book_id' => $book->id,
                'book_version_id' => $version->id,
                'chapter' => $request->chapter,
                'verse' => $request->verse,
                'text' => $request->text,
            ]);

            if ($request->tags) {

                $tags = collect($request->tags)->map(function ($tag) use ($scripture) {
                    return [
                        'scripture_id' => $scripture->id,
                        'tag' => $tag,
                    ];
                })->toArray();

                Tag::insert($tags);
            }

            $devotion = Devotion::create([
                'scripture_id' => $scripture->id,
                'favorite' => $request->isFavorite,
                'personal_reflection' => $request->personalReflection,
                'prayer_request' => $request->prayerRequest,
                'application_notes' => $request->applicationNotes,
                'mood' => $request->mood,
            ]);

            $createdId = $scripture->id;
        });

        if (!$createdId) {
            throw new \Exception("Failed to create scripture.");
        }

        $scripture = Scripture::where('id', $createdId)->first();
        return GetScriptureResource::make($scripture);
    }

    public function updateScripture(int $id, Request $request)
    {
        $request->validate([
            'personalReflection' => 'nullable',
            'prayerRequest' => 'nullable',
            'applicationNotes' => 'nullable',
        ]);

        DB::transaction(
            function () use ($id, $request) {
                $devotion = Devotion::where('scripture_id', $id)->first();
                if ($devotion) {
                    $devotion->update([
                        // 'scripture_id' => $id,
                        // 'favorite' => $request->isFavorite,
                        'personal_reflection' => $request->personalReflection,
                        'prayer_request' => $request->prayerRequest,
                        'application_notes' => $request->applicationNotes,
                        // 'mood' => $request->mood,
                    ]);
                }
            }
        );

        $scripture = Scripture::where('id', $id)->first();
        return GetScriptureResource::make($scripture);
    }

    public function getScripture(int $id, Request $request)
    {
        $scripture = Scripture::with('devotion')->where('id', $request->id)->first();
        return GetScriptureResource::make($scripture);
    }

    public function deleteScripture(int $id, Request $request)
    {
        $scripture = Scripture::with(['devotion', 'tags'])->findOrFail($id);

        if ($scripture->tags()->exists()) {
            $scripture->tags()->delete();
        }

        if ($scripture->devotion) {
            $scripture->devotion()->delete();
        }

        $scripture->delete();

        return GetScriptureResource::make($scripture);
    }

    public function getFavorites(Request $request)
    {
        $scriptures = Scripture::with('devotion')
            ->whereHas('devotion', function ($query) {
                $query->where('favorite', true);
            })
            ->get();

        return GetScriptureResource::collection($scriptures);
    }

    public function addToFavorites(Request $request)
    {
        $scripture = Scripture::with('devotion')->where('id', $request->id)->first();

        $devo = $scripture->devotion ?? null;

        if ($devo) {
            $devo->update([
                'favorite' => !$devo->favorite,
            ]);
        }

        return GetScriptureResource::make($scripture);
    }

    public function getRecent(Request $request)
    {
        $scriptures = Scripture::orderBy('created_at', 'desc')
            ->simplePaginate(10);

        return GetScriptureResource::collection($scriptures);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ScriptureService;

class ScripturesController extends Controller
{
    public function getScriptures(Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->getScriptures($request);
    }

    public function getScripture(int $id, Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->getScripture($id, $request);
    }

    public function updateScripture(int $id, Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->updateScripture($id, $request);
    }

    public function createScripture(Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->createScripture($request);
    }

    public function deleteScripture(int $id, Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->deleteScripture($id, $request);
    }

    public function getFavorites(Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->getFavorites($request);
    }

    public function getRecent(Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->getRecent($request);
    }

    public function addToFavorites(Request $request, ScriptureService $scriptureService)
    {
        return $scriptureService->addToFavorites($request);
    }
}

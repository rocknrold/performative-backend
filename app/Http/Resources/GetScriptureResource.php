<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetScriptureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'book' => $this->book->name ?? "",
            'version' => $this->version->abbreviation ?? "",
            'chapter' => $this->chapter,
            'verse' => $this->verse,
            'text' => $this->text,
            'tags' => [],
            'notes' => $this->notes,
            'journalId' => $this->id,
            'personalReflection' => $this->devotion->personal_reflection ?? "",
            'prayerRequest' => $this->devotion->prayer_request ?? "",
            'applicationNotes' => $this->devotion->application_notes ?? "",
            'isFavorite' => $this->devotion->favorite ?? false,
            'mood' => $this->devotion->mood ?? "",
            'studyDate' => $this->devotion->studyDate ?? "",
            'createdAt' => $this->created_at->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updated_at->format('Y-m-d H:i:s'),
        ];

        return $data;
    }
}

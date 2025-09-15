<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDevotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'journalId' => $this->id,
            'personalReflection' => $this->personal_reflection ?? "",
            'prayerRequest' => $this->prayer_request ?? "",
            'applicationNotes' => $this->application_notes ?? "",
            'isFavorite' => $this->favorite ?? false,
            'mood' => $this->mood,
            'studyDate' => $this->studyDate,
        ];
    }
}

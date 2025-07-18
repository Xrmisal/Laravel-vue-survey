<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Resources\Json\JsonResource;
use DateTime;

class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->image ? URL::to($this->image) : null,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status == 1,
            'description' => $this->description,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new DateTime($this->updated_at))->format('Y-m-d H:i:s'),
            'expire_date' => (new DateTime($this->expire_date))->format('Y-m-d'),
            'questions' => SurveyQuestionResource::collection($this->questions),
        ];
    }
}

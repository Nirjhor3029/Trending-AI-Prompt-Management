<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class PromptResource extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request);
         return [
            // "id"    => $this->id ?? null,
            "title" => $this->title,
            "text"  => $this->text,

            "images" => $this->getMedia('images')->map(function ($media) {
                return [
                    "url"       => $media->getUrl(),
                    "thumbnail" => $media->getUrl('thumb'),
                    "preview"   => $media->getUrl('preview'),
                ];
            })->values(),

            "ai_platforms" => $this->whenLoaded('ai_platforms', function () {
                return $this->ai_platforms->map(function ($platform) {
                    return [
                        "id"   => $platform->id ?? null,
                        "name" => $platform->name ?? null,
                        "url"  => $platform->url ?? null,
                    ];
                })->values();
            }),
        ];
    }
}

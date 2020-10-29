<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'posts',
            'id' => $this->resource->getRoutekey(),
            'attributes' => [
                'title' => $this->resource->title,
                'slug' => $this->resource->slug,
//                'excerpt' => $this->resource->excerpt,
//                'published_at' => $this->published_at,
            ]
        ];
    }
}

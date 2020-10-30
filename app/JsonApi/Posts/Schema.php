<?php

namespace App\JsonApi\Posts;

use App\Models\Post;
use Neomerx\JsonApi\Schema\SchemaProvider;

class Schema extends SchemaProvider
{

    /**
     * @var string
     */
    protected $resourceType = 'posts';

    /**
     * @param Post $resource
     *      the domain record being serialized.
     * @return string
     */
    public function getId($resource)
    {
        return (string) $resource->getRouteKey();
    }

    /**
     * @param Post $resource
     *      the domain record being serialized.
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'title'=>$resource->title,
            'slug' => $resource->slug,
            'excerpt' => $resource->excerpt,
            'publishedAt' => $resource->published_at->toAtomString(),
            'createdAt' => $resource->created_at->toAtomString(),
            'updatedAt' => $resource->updated_at->toAtomString(),
        ];
    }
}

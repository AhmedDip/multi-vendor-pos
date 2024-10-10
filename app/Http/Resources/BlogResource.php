<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    final public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'shop_id'   => $this->shop_id,
            'title'     => $this->title,
            'slug'      => $this->slug,
            'content'   => $this->content,
            'summary'   => $this->summary,
            'tag'       => $this->tag,
            'is_comment_allowed' => $this->is_comment_allowed,
            'click'     => $this->click,
            'impression'=> $this->impression,
            'photo'     => get_image($this?->photo?->photo),
            'photo_name'       => $this?->photo?->photo,
        ];
    }
}

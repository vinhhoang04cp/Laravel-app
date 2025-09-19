<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        // Voyager lưu image là path, convert sang URL public nếu có
        $imageUrl = $this->image
            ? (str_starts_with($this->image, 'http') ? $this->image : Storage::disk('public')->url($this->image))
            : null;

        return [
            'id'         => $this->id,
            'author_id'  => $this->author_id,
            'category_id'=> $this->category_id,
            'title'      => $this->title,
            'slug'       => $this->slug,
            'excerpt'    => $this->excerpt,
            'body'       => $this->body,
            'image'      => $imageUrl,
            'status'     => $this->status,   // 'PUBLISHED' | 'DRAFT' theo Voyager
            'featured'   => (bool) $this->featured,
            'meta'       => $this->meta,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}

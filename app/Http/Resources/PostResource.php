<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'short_title' => $this->shortTitle,
            'desc' => $this->desc,
            'slug' => $this->slug,
            'body' => $this->body,
            'status' => $this->status ?? 'DRAFT',
            'tags' => TagResource::collection($this->tags),
            'clap' => $this->totalClap,
            'created_at' => $this->date,
            'user' => [
                'id' => $this->user->id,
                'userid' => $this->user->username ?? $this->user->id,
                'name' => $this->user->name,
                'avatar' => $this->user->avatar,
            ],
        ];
    }
}

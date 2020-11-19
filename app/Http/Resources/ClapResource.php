<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClapResource extends JsonResource
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
            'total_clap' => $this->total,
            'user' => $this->when($request->get('clap-with-users'), function () {
                return new UserResource($this->user);
            })
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClapRequest;
use App\Http\Resources\ClapResource;
use App\Http\Resources\UserResource;
use App\Models\Clap;
use App\Models\Post;

class ClapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Create new or Update total clap for post per user
     * 
     * @param   \App\Http\Requests\ClapRequest
     * @return  \App\Http\Resources\ClapResource
     */
    public function claps(ClapRequest $request)
    {
        try {
            // Check if post exist and published
            $post = Post::where('id', $request->post_id)
                ->where('status', 'PUBLISH')
                ->firstOrFail();

            // Get clap from db
            $clap = Clap::where('user_id', $request->user_id)
                ->where('post_id', $post->id)
                ->first();

            // Create new clap if null
            if (is_null($clap)) {
                $validatedData = $request->validated();
                $newClap = Clap::create($validatedData);

                return new ClapResource($newClap);
            }

            // Otherwise, add total clap if lower than max clap
            if (!is_null($clap) && (int)$clap->total < Clap::MAX_USER_CLAP) {
                $clap->total = $clap->total + $request->total;
                $clap->save();
            }

            return new ClapResource($clap);
        } catch (\ErrorException $error) {
            return $error;
        }
    }

    /**
     * Display all clap by post id
     * 
     * @param   int $postId
     * @return  \App\Http\Resources\ClapResource
     */
    public function postClaps($postId)
    {
        $claps = Clap::where('post_id', $postId)
            ->orderBy('created_at', 'desc')
            ->get();

        request()->request->add(['clap-with-users' => true]);

        return ClapResource::collection($claps);
    }
}

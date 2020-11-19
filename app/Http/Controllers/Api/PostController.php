<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \App\Http\Resources\PostResource
     */
    public function index()
    {
        return PostResource::collection(Post::with('tags')->orderBy('created_at', 'desc')->get());
    }

    /**
     * Display a listing of the resource with status filter.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $status
     * @return \Illuminate\Http\Response
     * @return \App\Http\Resources\PostResource
     */
    public function status(Request $request, $status)
    {
        $this->authorize('view-all-status', Post::class);

        if (!in_array(strtoupper($status), ['DRAFT', 'PUBLISH', 'ARCHIVE'])) {
            return response()->json([
                'error' => 'Parameter is not valid post status value'
            ]);
        }

        $posts = Post::where('user_id', $request->user()->id)
            ->where('status', strtoupper($status))
            ->orderBy('created_at', 'desc')
            ->get();

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @return \App\Http\Resources\PostResource
     */
    public function store(PostRequest $request)
    {
        $this->authorize('create', Post::class);

        try {
            $validatedData = $request->validated();
            $post = Post::create($validatedData);

            // create tags if request tags is not null
            $tags = $request->tags;
            if (!is_null($tags) && count($tags) > 0) {
                foreach ($request->tags as $tag) {
                    // if tag is exist get tag or create it
                    $tag = Tag::firstOrCreate(['title' => $tag, 'slug' => Str::slug($tag)]);
                    // attach tag to post model
                    $post->tags()->attach($tag);
                }
            }

            return new PostResource($post);
        } catch (\ErrorException $error) {
            return $error;
        }
    }

    /**
     * Display the specified published resource.
     *
     * @param  string  $slug
     * @return \App\Http\Resources\PostResource
     */
    public function show($slug)
    {
        return new PostResource(
            Post::where('slug', $slug)
                ->where('status', 'PUBLISH')
                ->firstOrFail()
        );
    }

    /**
     * Display the specified resource for editing.
     *
     * @param  string  $slug
     * @return \App\Http\Resources\PostResource
     */
    public function showEdit(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        $this->authorize('show-edit', $post);

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PostRequest  $request
     * @param  int  $id
     * @return \App\Http\Resources\PostResource
     */
    public function update(PostRequest $request, $id)
    {
        $validatedData = $request->validated();
        $post = Post::where('id', $id)->firstOrFail();

        $this->authorize('update', $post);

        try {
            // if tag request is null detach all tags from specific post
            if (is_null($request->tags) || count($request->tags) > 0) {
                $post->tags()->detach();
            }

            // update post with validated request
            $post->update($validatedData);

            return new PostResource($post);
        } catch (\ErrorException $error) {
            return $error;
        }
    }

    /**
     * Bookmark post
     * 
     * @param  \Illuminate\Http\Request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function bookmark(Request $request, $id)
    {
        $post = Post::where('id', $id)->where('status', 'PUBLISH')->firstOrFail();

        $this->authorize('bookmark', $post);

        try {
            $post->bookmarks()->attach($request->user());
            return response()->json([
                'message' => 'Success bookmark this post.',
            ]);
        } catch (\ErrorException $error) {
            return $error;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id)->firstOrFail();

        $this->authorize('delete', $post);

        try {
            $post->delete();
        } catch (\ErrorException $error) {
            return $error;
        }
    }
}

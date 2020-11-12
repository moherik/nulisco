<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopicRequest;
use App\Http\Resources\TopicResource;
use App\Models\Topic;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TopicResource::collection(Topic::orderBy('created_at', 'desc')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Topic\TopicRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TopicRequest $request)
    {
        $validatedData = $request->validated();
        return new TopicResource(Topic::create($validatedData));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $topic = Topic::where('id', $id)->firstOrFail();
        return new TopicResource($topic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TopicRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TopicRequest $request, $id)
    {
        $validatedData = $request->validated();
        $topic = Topic::where('id', $id)->firstOrFail();
        $topic->update($validatedData);
        return new TopicResource($topic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $topic = Topic::where('id', $id)->firstOrFail();
        if (!$topic->delete()) {
            return response()->json([
                'error' => 'Error deleteing data',
            ]);
        }
    }
}

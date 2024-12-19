<?php

namespace App\Http\Controllers;

use App\Models\Tweet;
use App\Models\Handle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TweetController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /tweets
     */
    public function index()
    {
        $tweets = Tweet::all();
        return response()->json($tweets, 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /tweets
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'handle_id' => 'required|exists:handles,id',
            'tweet_id' => 'required|string|max:255|unique:tweets,tweet_id',
            'content' => 'required|string',
            'processed' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tweet = Tweet::create([
            'handle_id' => $request->input('handle_id'),
            'tweet_id' => $request->input('tweet_id'),
            'content' => $request->input('content'),
            'processed' => $request->input('processed', false),
            'created_at' => now() // assuming you want to set creation time to now if not provided
        ]);

        return response()->json([
            'message' => 'Tweet created successfully',
            'data' => $tweet
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /tweets/{id}
     */
    public function show($id)
    {
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json(['error' => 'Tweet not found'], 404);
        }

        return response()->json($tweet, 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /tweets/{id} or PATCH /tweets/{id}
     */
    public function update(Request $request, $id)
    {
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json(['error' => 'Tweet not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'handle_id' => 'sometimes|required|exists:handles,id',
            'tweet_id' => 'sometimes|required|string|max:255|unique:tweets,tweet_id,' . $tweet->id,
            'content' => 'sometimes|required|string',
            'processed' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('handle_id')) {
            $tweet->handle_id = $request->input('handle_id');
        }

        if ($request->has('tweet_id')) {
            $tweet->tweet_id = $request->input('tweet_id');
        }

        if ($request->has('content')) {
            $tweet->content = $request->input('content');
        }

        if ($request->has('processed')) {
            $tweet->processed = $request->input('processed');
        }

        $tweet->save();

        return response()->json([
            'message' => 'Tweet updated successfully',
            'data' => $tweet
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /tweets/{id}
     */
    public function destroy($id)
    {
        $tweet = Tweet::find($id);

        if (!$tweet) {
            return response()->json(['error' => 'Tweet not found'], 404);
        }

        $tweet->delete();

        return response()->json(['message' => 'Tweet deleted successfully'], 200);
    }
}

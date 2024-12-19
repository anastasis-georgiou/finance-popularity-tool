<?php

namespace App\Http\Controllers;

use App\Models\Handle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HandleController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /handles
     */
    public function index()
    {
        $handles = Handle::all();
        return response()->json($handles, 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /handles
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'handle' => 'required|string|max:255|unique:handles,handle',
            'crawling_freq' => 'nullable|integer|min:60',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $handle = Handle::create([
            'handle' => $request->input('handle'),
            'crawling_freq' => $request->input('crawling_freq', 3600),
            'last_crawled_at' => null
        ]);

        return response()->json([
            'message' => 'Handle added successfully',
            'data' => $handle
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /handles/{id}
     */
    public function show($id)
    {
        $handle = Handle::find($id);

        if (!$handle) {
            return response()->json(['error' => 'Handle not found'], 404);
        }

        return response()->json($handle, 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /handles/{id} or PATCH /handles/{id}
     */
    public function update(Request $request, $id)
    {
        $handle = Handle::find($id);

        if (!$handle) {
            return response()->json(['error' => 'Handle not found'], 404);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'handle' => 'sometimes|required|string|max:255|unique:handles,handle,' . $handle->id,
            'crawling_freq' => 'sometimes|integer|min:60',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update handle
        if ($request->has('handle')) {
            $handle->handle = $request->input('handle');
        }

        if ($request->has('crawling_freq')) {
            $handle->crawling_freq = $request->input('crawling_freq');
        }

        $handle->save();

        return response()->json([
            'message' => 'Handle updated successfully',
            'data' => $handle
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /handles/{id}
     */
    public function destroy($id)
    {
        $handle = Handle::find($id);

        if (!$handle) {
            return response()->json(['error' => 'Handle not found'], 404);
        }

        $handle->delete();

        return response()->json(['message' => 'Handle deleted successfully'], 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InstrumentController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /instruments
     */
    public function index()
    {
        $instruments = Instrument::all();
        return response()->json($instruments, 200);
    }

    /**
     * Store a newly created resource in storage.
     * POST /instruments
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'symbol' => 'required|string|max:50|unique:instruments,symbol'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $instrument = Instrument::create([
            'symbol' => $request->input('symbol')
        ]);

        return response()->json([
            'message' => 'Instrument created successfully',
            'data' => $instrument
        ], 201);
    }

    /**
     * Display the specified resource.
     * GET /instruments/{id}
     */
    public function show($id)
    {
        $instrument = Instrument::find($id);

        if (!$instrument) {
            return response()->json(['error' => 'Instrument not found'], 404);
        }

        return response()->json($instrument, 200);
    }

    /**
     * Update the specified resource in storage.
     * PUT /instruments/{id} or PATCH /instruments/{id}
     */
    public function update(Request $request, $id)
    {
        $instrument = Instrument::find($id);

        if (!$instrument) {
            return response()->json(['error' => 'Instrument not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'symbol' => 'sometimes|required|string|max:50|unique:instruments,symbol,' . $instrument->id
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($request->has('symbol')) {
            $instrument->symbol = $request->input('symbol');
        }

        $instrument->save();

        return response()->json([
            'message' => 'Instrument updated successfully',
            'data' => $instrument
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /instruments/{id}
     */
    public function destroy($id)
    {
        $instrument = Instrument::find($id);

        if (!$instrument) {
            return response()->json(['error' => 'Instrument not found'], 404);
        }

        $instrument->delete();

        return response()->json(['message' => 'Instrument deleted successfully'], 200);
    }
}

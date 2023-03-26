<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\PlayerType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayerTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playerTypes = PlayerType::all();

        return response()->json([
            'success' => true,
            'data' => $playerTypes,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $playerType = PlayerType::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $playerType,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerType $playerType)
    {
        return response()->json([
            'success' => true,
            'data' => $playerType,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PlayerType $playerType)
    {
        $request->validate([
            'description' => 'string',
        ]);

        $playerType->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $playerType,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerType $playerType)
    {
        $playerType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item type deleted successfully',
        ]);
    }
}

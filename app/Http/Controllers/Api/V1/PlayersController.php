<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = Player::all();

        return response()->json([
            'success' => true,
            'data' => $players,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'              => 'required|string',
            'email'             => 'required|email',
            'player_type_id'    => 'required|integer',
            'life'              => 'integer',
            'attack_points'     => 'integer',
            'defense_points'    => 'integer',

        ]);

        $player = Player::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $player,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return response()->json([
            'success' => true,
            'data' => $player,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        $request->validate([
            'name'              => 'string',
            'email'             => 'email',
            'player_type_id'    => 'integer',
            'life'              => 'integer',
            'attack_points'     => 'integer',
            'defense_points'    => 'integer',

        ]);

        $player->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $player,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        $player->delete();

        return response()->json([
            'success' => true,
            'message' => 'Player type deleted successfully',
        ]);
    }
}

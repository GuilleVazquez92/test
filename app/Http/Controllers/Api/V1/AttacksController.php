<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Attack;
use App\Models\AttackType;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;

class AttacksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attacks = Attack::all();

        return response()->json([
            'success' => true,
            'data' => $attacks,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attacker_id'        => 'required|integer',
            'defender_id'        => 'required|integer',
            'attack_type_id'     => 'required|integer',
            'damage'            => 'numeric'

        ]);

        // DATOS DEL ATANQUE  //
        $attack_type    = $request->get('attack_type_id');
        $attacker_id    = $request->get('attacker_id');
        $attack_power   = AttackType::find($attack_type);

        // DATOS DEL DEFENSOR // 
        $defender_id    = $request->get('defender_id');



        if ($attacker_id != $defender_id) { // VERIFICA SI NO SON DEL MISMO ID

            // GENERAR PUNTOS DE ATAQUE              ///

            $attacker_item           = Player::with(['item' => function ($query) {
                $query->wherePivot('equipped', '=', 1);
            }])->find($attacker_id);

            if ($attacker_item['life'] > 0) {

                $attack_points           = $attacker_item->item->sum('attack_points') + $attacker_item->attack_points;

                // GENERAR PUNTOS DE DEFENSA            //

                $defender_item           = Player::with(['item' => function ($query) {
                    $query->wherePivot('equipped', '=', 1);
                }])->find($defender_id);

                $defender_points           = $defender_item->item->sum('defense_points') + $defender_item->defense_points;


                // GENERAR PUNTOS DE VIDA A SER DESCONTADOS

                $result =  $attack_points - $defender_points;

                $damage = $result > 0 ? $result : 1;

                // VERIFICA SI EL DEFENSOR TIENE AUN VIDA

                if ($defender_item['life'] > 0) {

                    // TRAER CUAL FUE EL ULTIMO ATAQUE REALIZAFO DEL ATACANTE
                    $latest_attack  = Attack::where('attacker_id', $attacker_id)->latest()->first();

                    // VERIFICAR SI ESTA TRATANDO DE ENVIAR UN ATQUE TIPO ULTI
                    if ($attack_type == 3) {
                        if (isset($latest_attack) and $latest_attack['attack_type_id'] == 1) {

                            $attack_points           = $attack_points * $attack_power['power'];
                            $result =  $attack_points - $defender_points;
                            $request['damage'] = $result > 0 ? $result : 1;

                            $attack = Attack::create($request->all());
                        } else {
                            return response()->json([
                                'success' => false,
                                'message' => 'To use the Ulti attack, his last attack must have been a melee attack.',
                            ], 200);
                        }
                    } else {
                        $attack_points           = $attack_points * $attack_power['power'];
                        $result =  $attack_points - $defender_points;
                        $request['damage'] = $result > 0 ? $result : 1;

                        $attack = Attack::create($request->all());
                    }

                    $life_points_update = $defender_item['life'] - $request['damage'];

                    if ($life_points_update > 0) {
                        Player::where('id', $defender_id)->update(['life' => $life_points_update]);
                    } else {
                        Player::where('id', $defender_id)->update(['life' => 0]);
                    }
                    return response()->json([
                        'success' => true,
                        'data' => $attack,
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "The enemy is already dead, you can no longer attack him",
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "You can't launch attacks because you're already dead",
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "You can't attack yourself",
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($attacker_id)
    {
        $attacks = Attack::where('attacker_id', $attacker_id)->get();
        return response()->json([
            'success' => true,
            'data' => $attacks,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attack $attack)
    {
        $request->validate([
            'attacker_id'        => 'integer',
            'defender_id'        => 'integer',
            'attack_type_id'     => 'integer',
            'damage'            => 'numeric'

        ]);

        $attack->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $attack,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attack $attack)
    {
        $attack->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attack deleted successfully',
        ]);
    }
}

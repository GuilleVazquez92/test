<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventories\InventoryStoreRequest;
use App\Http\Requests\UpdateRequest;

class InventoriesController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::all();

        return response()->json([
            'success' => true,
            'data' => $inventories,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(InventoryStoreRequest $request)
    {

        $inventory = Inventory::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $inventory,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return response()->json([
            'success' => true,
            'data' => $inventory,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Inventory $inventory)
    {
        
        $inventory->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $inventory,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventorytype deleted successfully',
        ]);
    }
}


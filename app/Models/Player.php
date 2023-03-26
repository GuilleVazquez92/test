<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'player_type_id',
        'life',
        'attack_points',
        'defense_points',
    ];

    public function playerType()
    {
        return $this->belongsTo(PlayerType::class);
    }

    public function item()
    {
        return $this->belongsToMany(Item::class, 'inventories');
    }
}

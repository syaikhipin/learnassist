<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{   protected $casts = [

    'date' => 'datetime',
];


    public static function getByUuid(int $workspace_id, $uuid)
    {
        return Todo::where("workspace_id", $workspace_id)
            ->where("uuid", $uuid)
            ->first();
    }
    use HasFactory;
}

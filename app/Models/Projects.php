<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{    protected $casts = [
    'start_date' => 'datetime',
    'end_date' => 'datetime',
];

    public static function getByUuid(int $workspace_id, $uuid)
    {
        return Projects::where("workspace_id", $workspace_id)
            ->where("uuid", $uuid)
            ->first();
    }

    use HasFactory;
}

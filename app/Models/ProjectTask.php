<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{   protected $casts = [

    'date' => 'datetime',
];


    public static function getByUuid(int $workspace_id, $uuid)
    {
        return ProjectTask::where("workspace_id", $workspace_id)
            ->where("uuid", $uuid)
            ->first();
    }
    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyGoal extends Model
{

    public static function getByUuid(int $workspace_id, $uuid)
{
    return StudyGoal::where("workspace_id", $workspace_id)
        ->where("uuid", $uuid)
        ->first();
}
    use HasFactory;
}

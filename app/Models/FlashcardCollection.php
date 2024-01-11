<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashcardCollection extends Model
{
    use HasFactory;

    public static function getByUuid(mixed $workspace_id, $uuid)
    {
        return self::where('workspace_id', $workspace_id)
            ->where('uuid', $uuid)
            ->first();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $casts = [
        'features' => 'array',
        'modules' => 'array',
    ];
    public static function listForSuperAdmin()
    {
        return self::all();
    }

    public static function getByUuid($uuid)
    {
        return self::where('uuid', $uuid)->first();
    }
}

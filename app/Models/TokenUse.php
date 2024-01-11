<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TokenUse extends Model
{
    use HasFactory;

    public static function addForWorkspace($workspace_id, int $total_token_usage, $type = 'text')
    {
        if($total_token_usage > 0)
        {
            $usage = new self();
            $usage->workspace_id = $workspace_id;
            $usage->token_count = $total_token_usage;
            $usage->type = $type;
            $usage->save();
        }

    }

    public static function getUsageThisMonth($workspace_id, $type)
    {
        return TokenUse::where('workspace_id', $workspace_id)
            ->where('type', $type)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->count();
    }

    public static function getUsageLastThirtyDays($workspace_id, $type)
    {
        return TokenUse::where('workspace_id', $workspace_id)
            ->where('type', $type)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->sum('token_count');
    }

    public static function getAllTimeTotal(int $workspace_id)
    {
        return TokenUse::where('workspace_id', $workspace_id)
            ->sum('token_count');
    }

    public static function getUsageCountsEachDayThisPeriod(int $workspace_id)
    {
        $usage = TokenUse::where('workspace_id', $workspace_id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); // grouping by years
            });

        $usage_counts = [];

        foreach($usage as $key => $value)
        {
            $usage_counts[$key] = $value->sum('token_count');
        }

        return $usage_counts;


    }
}

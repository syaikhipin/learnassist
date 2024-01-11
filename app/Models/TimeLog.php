<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    use HasFactory;
    protected $casts = [
        'timer_started_at' => 'datetime',
        'timer_stopped_at' => 'datetime',
    ];

    public static function getStudyTrendsLast7Days(int $workspace_id, $id)
    {
        $logs = TimeLog::where('workspace_id', $workspace_id)
            ->where('user_id', $id)
            ->where('timer_started_at', '>=', now()->subDays(7))
            ->get();


        $data = [];

        foreach ($logs as $log) {
            $date = $log->timer_started_at->format('D');
            if (!isset($data[$date])) {
                $data[$date] = 0;
            }
            $data[$date] += $log->timer_duration;
            //Convert to minutes
            $data[$date] = round($data[$date] / 60, 2);
        }

        return $data;
    }
}

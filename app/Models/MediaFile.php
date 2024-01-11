<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{

    public static function getByUuid($workspace_id, $uuid)
    {
        return self::where('workspace_id', $workspace_id)
            ->where('uuid', $uuid)
            ->first();
    }
    public static function getForWorkspace($workspace_id, $type = null, $is_ai_generated = 0)
    {
        $query = self::where('workspace_id', $workspace_id);
        if($type)
        {
            if($type === 'image')
            {
                $query->where('mime_type','like','image/%');
            }
            else if($type === 'video')
            {
                $query->where('mime_type','like','video/%');
            }
            else if($type === 'audio')
            {
                $query->where('mime_type','like','audio/%');
            }
            else if($type === 'document')
            {
                $query->where('mime_type','like','application/%');
            }
        }
        if($is_ai_generated)
        {
            $query->where('is_ai_generated', $is_ai_generated);
        }
        return $query->get();
    }

    public static function listForSuperAdmin()
    {
        return self::all();
    }

    public static function hasExceedStorageLimit($workspace, $file_size)
    {
        if($workspace->plan_id)
        {
            $plan = SubscriptionPlan::find($workspace->plan_id);
            if($plan && $plan->file_space_limit > 0)
            {
                $total_storage_space_used = MediaFile::where('workspace_id', $workspace->id)->sum('size');
                $total_storage_space_used = $total_storage_space_used ?? 0;
                $total_storage_space_used += $file_size;
                // Convert to MB
                $total_storage_space_used = $total_storage_space_used / 1024 / 1024;

                ray($total_storage_space_used, $plan->file_space_limit);

                if($total_storage_space_used > $plan->file_space_limit)
                {
                    return true;
                }

            }
        }

        return false;

    }

    public static function totalStorageSpaceUsed($workspace_id)
    {
        $total_storage_space_used = MediaFile::where('workspace_id', $workspace_id)->sum('size');
        $total_storage_space_used = $total_storage_space_used ?? 0;
        // Convert to MB
        $total_storage_space_used = $total_storage_space_used / 1024 / 1024;
        // Round to 2 decimal places
        return round($total_storage_space_used, 2);
    }
}

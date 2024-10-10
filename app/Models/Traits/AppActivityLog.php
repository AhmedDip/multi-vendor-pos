<?php

namespace App\Models\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use JsonException;

trait AppActivityLog
{
    /**
     * @throws JsonException
     */
    final public static function activityLog(Request $request, array $original, array $changed, Model $model): void
    {
        (new ActivityLog())->store_activity_log($request, $original, $changed, $model);
    }
}

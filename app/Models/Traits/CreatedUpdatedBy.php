<?php

namespace App\Models\Traits;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use JsonException;

trait CreatedUpdatedBy
{
    final public static function bootCreatedUpdatedBy(): void
    {
        static::creating(static function ($model) {
            $model->created_by_id = auth()->user()->id ?? 1;
        });
        static::updating(static function ($model) {
            $model->updated_by_id = auth()->user()->id ?? 1;
        });
        static::deleting(static function ($model) {
            $model->updated_by_id = auth()->user()->id ?? 1;
            $model->save();
            Log::critical('DELETED_' . strtoupper(class_basename($model)), ['model_data' => $model->original, 'deleted_by' => auth()->user()]);
        });
    }
}

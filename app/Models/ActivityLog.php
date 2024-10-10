<?php

namespace App\Models;

use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JsonException;

class ActivityLog extends Model
{
    use HasFactory, CreatedUpdatedBy;

    protected $guarded = [];

    /**
     * @throws JsonException
     */
    final public function store_activity_log(Request $request, array|null $original, array|null $changed, Model $model): void
    {
        $data = $this->prepare_data($request, $original, $changed);
        if (!empty($data)) {
            $model->activity_logs()->create($data);
        }
    }

    /**
     * @throws JsonException
     */
    private function prepare_data(Request $request, array|null $original, array|null $changed): array
    {

        $data = [];
        if (!empty($changed) || ($request->input('note') && !empty($request->input('note')))) {

            unset($changed['updated_at'], $changed['password']);
            unset($original['password']);
            unset($original['photo'], $changed['photo']);
            $old_data = array_intersect_key($original, $changed);
            $data     = [
                'note'          => $request->input('note'),
                'action'        => $request->method(),
                'ip'            => $request->ip(),
                'route'         => $request->route()?->getName(),
                'method'        => $request->method(),
                'agent'         => $request->userAgent(),
                'old_data'      => json_encode($old_data, JSON_THROW_ON_ERROR),
                'new_data'      => json_encode($changed, JSON_THROW_ON_ERROR),
            ];
        } else {
            unset($original['password']);
            unset($original['photo'], $changed['photo']);
            $data = [
                'note'          => $request->input('note'),
                'action'        => $request->method(),
                'ip'            => $request->ip(),
                'route'         => $request->route()?->getName(),
                'method'        => $request->method(),
                'agent'         => $request->userAgent(),
                'new_data'      => json_encode($original, JSON_THROW_ON_ERROR),
            ];
        }
        return $data;
    }

    /**
     * @return BelongsTo
     */
    final public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    final public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id');
    }

    /**
     * @return MorphTo
     */
    final public function creatable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return MorphTo
     */
    final public function logable(): MorphTo
    {
        return $this->morphTo();
    }
}

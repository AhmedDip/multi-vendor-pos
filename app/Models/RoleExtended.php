<?php

namespace App\Models;

use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class RoleExtended extends Role implements HasMiddleware
{
    use HasFactory;

    protected $guarded = [];

        public const STATUS_ACTIVE = 1;
        public const STATUS_INACTIVE = 2;

        public const STATUS_LIST = [
            self::STATUS_ACTIVE   => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];

        public const ROLE_TYPE_ADMIN = 1;
        public const ROLE_TYPE_USER = 2;

        public const ROLE_TYPE_LIST = [
            self::ROLE_TYPE_ADMIN => 'Admin',
            self::ROLE_TYPE_USER => 'User',
        ];


    /**
     * @param Request $request
     * @param bool $all
     * @param bool $no_filter
     * @return LengthAwarePaginator|Collection
     */
    final public function get_roles(Request $request, bool $all = false, bool $no_filter = false): LengthAwarePaginator|Collection
    {
        $query = self::query();
        if ($no_filter) {
            if ($all) {
                return $query->get();
            }
            return $query->paginate($request->input('per_page', 10));
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }
        if ($all) {
            return $query->get();
        }
        return $query->paginate($request->input('per_page', 20));
    }

    /**
     * @param int $id
     * @return Collection|Model|null
     */
    final public function get_role_by_id(int $id): Collection|Model|null
    {
        return self::query()->find($id);
    }

    /**
     * @param string $id
     * @return Collection|Model|null
     */
    final public function get_role_by_name(string $id): Collection|Model|null
    {
        return self::query()->where('name', 'like', '%' . $id . '%')->first();
    }

    /**
     * @param Request $request
     * @return Model
     */
    final public function create_role(Request $request): Model
    {
        return self::query()->create($request->all());
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return bool
     */
    final public function update_role(Request $request, Role $role): bool
    {
        return $role->update($request->all());
    }

    /**
     * @param Role $role
     * @return bool|null
     */
    final public function delete_role(Role $role): bool|null
    {
        return $role->delete();
    }

    /**
     * @return Collection
     */
    final public function get_roles_assoc(): Collection
    {
        return self::query()->pluck('name', 'id');
    }
    /**
     * @return Collection
     */
    final public function get_auth_user_roles_assoc(): Collection
    {
        return self::query()
            ->whereIn('name',  ['Vendor', 'Client', 'Agency'])
            ->pluck('name', 'id');
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

        final public function activity_logs(): MorphMany
        {
            return $this->morphMany(ActivityLog::class, 'logable')->orderByDesc('id');
        }

       /**
         * @return MorphOne
         */
        final public function photo(): MorphOne
        {
            return $this->morphOne(MediaGallery::class, 'imageable');
        }

        /**
         * @return MorphMany
         */
        final public function photos(): MorphMany
        {
            return $this->morphMany(MediaGallery::class, 'imageable');
        }

    public static function middleware()
    {
        // TODO: Implement middleware() method.
    }
}

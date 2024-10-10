<?php

namespace App\Models;

use App\Manager\Constants\GlobalConstant;
use App\Models\Traits\CreatedUpdatedBy;
use App\Observers\MenuObserver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

#[ObservedBy([MenuObserver::class])]
class Menu extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    /**
     * @param Request $request
     * @return LengthAwarePaginator
     */
    final public function get_menus(Request $request): LengthAwarePaginator
    {
        $query = self::query()->with(['parent_menu', 'parent_menu.parent_menu']);
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('route')) {
            $query->where('route', $request->input('route'));
        }
        if ($request->input('sort_order')) {
            $order_direction = $request->input('order_by') ?? 'desc';
            $query->orderBy($request->input('sort_order', 'id'), $order_direction);
        }

        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    /**
     * @param Request $request
     * @return Builder|Model
     */
    final public function store_menu(Request $request): Builder|Model
    {
        return self::query()->create($this->prepare_data($request));
    }

    /**
     * @param Request $request
     * @param Menu|Model $menu
     * @return bool
     */
    final public function update_menu(Request $request, Menu|Model $menu): bool
    {
        return $menu->update($this->prepare_data($request));
    }

    /**
     * @param Request $request
     * @return array
     */
    final public function prepare_data(Request $request): array
    {
        return [
            'name'         => $request->input('name'),
            'icon'         => $request->input('icon'),
            'sort_order'   => $request->input('sort_order'),
            'menu_id'      => $request->input('menu_id'),
            'route'        => $request->input('route'),
            'status'       => $request->input('status'),
            'query_string' => $request->input('query_string')
        ];
    }

    /**
     * @param Menu|Model $menu
     * @return void
     */

    final public function delete_menu(Menu|Model $menu): void
    {
        $menu->updated_by()->associate(auth()->user());
        $menu->save();
        $menu->delete();
    }

    /**
     * @param bool $is_active
     * @return Collection
     */
    final public function get_menu_assoc(bool $is_active = true): Collection
    {
        $query = self::query();
        if ($is_active) {
            $query->where('status', self::STATUS_ACTIVE);
        }
        return $query->pluck('name', 'id');
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

    /**
     * @return HasMany
     */
    final public function sub_menus(): HasMany
    {
        return $this->hasMany(__CLASS__, 'menu_id', 'id')->orderBy('sort_order');
    }

    /**
     * @return BelongsTo
     */
    final public function parent_menu(): BelongsTo
    {
        return $this->belongsTo(__CLASS__, 'menu_id', 'id')->orderBy('sort_order');
    }
}

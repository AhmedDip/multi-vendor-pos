<?php

namespace App\Models;

use App\Manager\Constants\GlobalConstant;
use App\Models\Traits\CreatedUpdatedBy;
use App\Observers\FeatureObserver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

#[ObservedBy(FeatureObserver::class)]
class Feature extends Model
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

    private function prepare_data(Request $request){
        return[
            'name'=>$request->input('name'),
            'sort_order'=>$request->input('sort_order'),
            'status'=>$request->input('status'),
            'slug'=> Str::slug($request->input('slug')),
            'shop_id'=>$request->input('shop_id'),
        ];
    }

    public function get_feature(Request $request):LengthAwarePaginator
    {
        $query = self::query();
        if ($request->input('shop_id')) {
            $query = self::query()->where('shop_id', $request->input('shop_id'));
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('sort_order') && !empty($request->input('sort_order'))) {
            $query->orderBy($request->input('sort_order', 'id'), $request->input('order_by', 'desc') ?? 'desc');
        }
        if ($request->input('order_by_column')) {
            $query->orderBy($request->input('order_by_column'),$request->input('order_by')?? 'DESC');
        } else {
            $query->orderBy('sort_order', 'asc');
        }
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_feature(Request $request){
        return self::query()->create($this->prepare_data($request));
    }

    public function update_feature(Request $request, Feature $feature){
        $feature->update($this->prepare_data($request));
    }

    public function delete_feature(Feature $feature){
        $feature->delete();
    }

    public function get_feature_for_api(Request $request){
        return cache()->rememberForever('features',function() use($request){
            return self::query()->where('status', self::STATUS_ACTIVE)->where('shop_id',$request->shop_id)->get();
        });
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}

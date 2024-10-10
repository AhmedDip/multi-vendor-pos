<?php

namespace App\Models;

use App\Manager\Constants\GlobalConstant;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Attribute extends Model
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

    private function prepare_data(Request $request)
    {
        return [
            'shop_id'     => $request->input('shop_id'),
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('slug')),
            'status'      => $request->input('status'),
            'sort_order'  => $request->input('sort_order'),
            'description' => $request->input('description'),
        ];
    }

    public function get_attribute(Request $request): LengthAwarePaginator
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
            $query->orderBy('id', 'desc');
        }
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_attribute(Request $request){
        return self::query()->create($this->prepare_data($request));
    }

    public function update_attribute(Request $request, attribute $attribute){
        $attribute->update($this->prepare_data($request));
    }

    public function delete_attribute(attribute $attribute){
        $attribute->delete();
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function get_attribute_assoc(){
        //return self::query()->where('status', self::STATUS_ACTIVE)->pluck('name', 'id');
        return self::query()->where('status', self::STATUS_ACTIVE)->get();
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    public function get_attribute_assoc_for_api(Request $request)
    {
        return self::query()
            ->where('shop_id', $request->shop_id)
            ->select('id', 'name')
            ->with(['values:id,attribute_id,name,slug'])
            ->get();
    }

    public function get_attribute_by_shop(Request $request){
        return self::query()->where('shop_id',$request->shop_id)->select('id','name')->get();
    }

    public function get_attribute_groupby_shop(){
        return self::query()->with('shop')->where('status', self::STATUS_ACTIVE)->get()->groupBy('shop_id');
    }
}

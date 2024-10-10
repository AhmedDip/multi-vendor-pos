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

class AttributeValue extends Model
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
            'attribute_id'=> $request->input('attribute_id'),
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('slug')),
            'status'      => $request->input('status'),
            'sort_order'  => $request->input('sort_order'),
            'description' => $request->input('description'),
        ];
    }

    public function get_attribute_value(Request $request): LengthAwarePaginator
    {
        $query = self::query()->orderBy('id', 'desc');
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
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_attribute_value(Request $request){
        return self::query()->create($this->prepare_data($request));
    }

    public function update_attribute_value(Request $request, AttributeValue $attribute_value){
        $attribute_value->update($this->prepare_data($request));
    }

    public function delete_attribute_value(AttributeValue $attribute_value){
        $attribute_value->delete();
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    public function get_attribute_value_assoc(){
        return self::query()->where('status', self::STATUS_ACTIVE)->pluck('name', 'id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_value_product');
    }


}

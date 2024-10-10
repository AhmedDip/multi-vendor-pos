<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Observers\DiscountObserver;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

#[ObservedBy(DiscountObserver::class)]
class Discount extends Model
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

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }

    private function prepare_data(Request $request){
        return[
            'name'=>$request->input('name'),
            'slug'=> Str::slug($request->input('slug')),
            'amount'=>$request->input('amount'),
            'percentage'=>$request->input('percentage'),
            'coupon_code'=>$request->input('coupon_code'),
            'shop_id'=>$request->input('shop_id'),
            'sort_order'=>$request->input('sort_order'),
            'status'=>$request->input('status'),
        ];
    }

    public function get_discount(Request $request):LengthAwarePaginator
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
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_discount(Request $request){
        return self::query()->create($this->prepare_data($request));
    }

    public function update_discount(Request $request, Discount $discount){
        $discount->update($this->prepare_data($request));
    }

    public function delete_discount(Discount $discount){
        $discount->delete();
    }

    // public function get_discount_for_api(Request $request): mixed
    // {
    //     return cache()->rememberForever('discounts',function() use ($request){
    //         return self::query()->where('status', self::STATUS_ACTIVE)->where('shop_id',$request->shop_id)->get();
    //     });
    // }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    // public function total_discount($shop_id = null){
    //     if($shop_id == null){
    //         return self::where('status',self::STATUS_ACTIVE)->count();
    //     }
    //     else{
    //         return self::where('status',self::STATUS_ACTIVE)->where('shop_id',$shop_id)->count();
    //     }
    // }
}

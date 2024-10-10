<?php

namespace App\Models;

use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Resources\TopSellingProductResource;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
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

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function total_sold($shopId = null, $startDate = null, $endDate = null)
    {

        $query = self::query();

        if ($shopId !== null) {
            $query->whereHas('order', function ($q) use ($shopId) {
                $q->where('shop_id', $shopId);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }

        $totalSold = $query->sum('quantity');

        return $totalSold;
    }






    public function top_selling_product($shop_id = null, $start_date = null, $end_date = null)
    {
        $query = $this->with(['product', 'photo'])
            ->join('products', 'products.id', '=', 'product_id')
            ->where('products.deleted_at', null)
            ->groupBy('product_id')
            ->selectRaw('sum(quantity) as total_sold, product_id')
            ->orderByDesc('total_sold')
            ->limit(5);

        if ($shop_id) {
            $query->whereHas('order', function ($q) use ($shop_id) {
                $q->where('shop_id', $shop_id);
            });
        }

        if ($start_date && $end_date) {
            $query->whereHas('order', function ($q) use ($start_date, $end_date) {
                $q->whereBetween('created_at', [$start_date, $end_date]);
            });
        }

        return $query->get();
    }





    public function assign_user()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }
}

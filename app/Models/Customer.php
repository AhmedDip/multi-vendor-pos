<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
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


    private function prepare_data(Request $request, Customer $customer = null): array
    {
        if ($customer) {
            $data['customer'] = [
                'name'               => $request->input('name') ?? $customer->name,
                'phone'              => $request->input('phone') ?? $customer->phone,
                'address'            => $request->input('address') ?? $customer->address,
                'shop_id'            => $request->input('shop_id') ?? $customer->shop_id,
                'membership_card_id' => $request->input('membership_card_id') ?? $customer->membership_card_id,
                'status'             => $request->input('status') ?? $customer->status,
                // 'sort_order'         => $request->input('sort_order')?? $customer->sort_order,

            ];
        } else {
            $data['customer'] = [
                'name'                     => $request->input('name'),
                'phone'                    => $request->input('phone'),
                'address'                  => $request->input('address'),
                'shop_id'                  => $request->input('shop_id'),
                // 'sort_order'               => $request->input('sort_order'),
                'membership_card_id'       => $request->input('membership_card_id'),
                'status'                   => $request->input('status') ?? self::STATUS_ACTIVE,
            ];
        }

        return $data;
    }

    final public function get_customer(Request $request)
    {
        $query = self::query();
        // $query = self::all();
        if ($request->input('shop_id')) {
            $query->where('shop_id', $request->input('shop_id'));
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }
        // if ($request->input('address')) {
        //     $query->where('address', 'like', '%' . $request->input('address') . '%');
        // }
        if ($request->input('membership_card_id')) {
            $query->where('membership_card_id', $request->input('membership_card_id'));
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        // return $query;
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_customer(Request $request)
    {
        $data           = $this->prepare_data($request);
        $customer       = self::query()->create($data['customer']);
        return $customer;
    }
    public function update_customer(Request $request, Customer $customer)
    {
        $data = $this->prepare_data($request, $customer);
        $customer->update($data['customer']);

        return true;
    }

    public function delete_customer(Model|Customer $customer)
    {
        return $customer->delete();
    }

    public function membershipCardType(): BelongsTo
    {
        return $this->belongsTo(MembershipCardType::class);
    }

    public function membershipCard()
    {
        return $this->belongsTo(MembershipCard::class);
    }

    /**
     * @return BelongsTo
     */

    public function membershipCardNo(): BelongsTo
    {
        return $this->belongsTo(MembershipCard::class, 'membership_card_id', 'id');
    }

    public function get_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    // public function total_customer($shop_id = null){
    //     if($shop_id == null){
    //         return self::where('status',self::STATUS_ACTIVE)->count();
    //     }
    //     else{
    //         return self::where('status',self::STATUS_ACTIVE)->where('shop_id',$shop_id)->count();
    //     }
    // }

    public function total_customer($shop_id = null, $start_date = null, $end_date = null)
    {
        $query = self::query();
        if ($shop_id !== null) {
            $query->where('shop_id', $shop_id);
        }
        // if ($start_date !== null && $end_date !== null) {
        //     $query->whereBetween('created_at', [$start_date, $end_date]);
        // }
        return $query->count();
    }

    public function getDiscount()
    {
        if ($this->membershipCard && $this->membershipCard->membershipCardType) {
            return $this->membershipCard->membershipCardType->discount;
        }
        return 0;
    }

    public function get_customer_info_by_phone($request, $phone)
    {
        $query = self::query();
        if ($request->input('shop_id')) {
            $query->where('shop_id', $request->input('shop_id'));
        }
        if ($phone) {
            $query->where('phone', $phone);
        }
        return $query->first();
    }

    public function get_customer_dependency_data($request)
    {
        return MembershipCard::query()->where('status', MembershipCard::STATUS_ACTIVE)
            ->where('shop_id', $request->header('shop-id'))
            ->get();

    }
}

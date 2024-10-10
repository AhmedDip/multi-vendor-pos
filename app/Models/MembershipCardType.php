<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MembershipCardType extends Model
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

        private function prepare_data(Request $request, MembershipCardType $membershipCardType = null): array
        {
            if ($membershipCardType) {
                $data['membershipCardType'] = [
                    'card_type_name' => $request->input('card_type_name') ?? $membershipCardType->card_type_name,
                    'status'         => $request->input('status')?? $membershipCardType->status,
                    'discount'       => $request->input('discount')?? $membershipCardType->discount,
                    'shop_id'        => $request->input('shop_id')?? $membershipCardType->shop_id,
                    // 'user_id'        => Auth::user()->id,
                    'user_id'        => $request->input('user_id')??Auth::user()->id,
                    // 'user_id'        => $request->input('user_id'),
                ];
            } else {
                $data['membershipCardType'] = [
                    'card_type_name' => $request->input('card_type_name'),
                    'status'         => $request->input('status') ?? self::STATUS_ACTIVE,
                    'discount'       => $request->input('discount'),
                    'shop_id'        => $request->input('shop_id'),
                    'user_id'        => $request->input('user_id')??Auth::user()->id,
                    // 'user_id'        => Auth::user()->id,
                    // 'user_id'        => $request->input('user_id'),
                    
                ];
            }

            return $data;
        }


        final public function get_membershipCardType(Request $request)
        {
            // dd($request->all());
            $query = self::query();
            if($request->input('shop_id')){
                $query->where('shop_id', $request->input('shop_id'));
            }
            if ($request->input('card_type_name')) {
                $query->where('card_type_name', 'like', '%' . $request->input('card_type_name') . '%');
            }
            if ($request->input('discount')) {
                $query->where('discount', $request->input('discount'));
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

        public function store_membershipCardType(Request $request){
            $data                = $this->prepare_data($request);
            $membershipCardType = self::query()->create($data['membershipCardType']);
            return $membershipCardType;

        }

        public function update_membershipCardType(Request $request, MembershipCardType $membershipCardType)
        {
            $data = $this->prepare_data($request, $membershipCardType);
            $membershipCardType->update($data['membershipCardType']);

            return true;
        }

        public function delete_membershipCardType(Model|MembershipCardType $membershipCardType)
        {
            return $membershipCardType->delete();
        }

        public function get_membershiptype(){
            return self::query()->where('status',self::STATUS_ACTIVE)->pluck('card_type_name','id' );  
        }

        public function get_shop():BelongsTo{

            return $this->belongsTo(Shop::class, 'shop_id','id');
        }
        public function get_membershiptype_for_card(){
            return self::query()
            ->where('status',self::STATUS_ACTIVE)
            ->get()
            ->groupBy('shop_id') ;
        }
        
        public function shop(): BelongsTo
        {
            return $this->belongsTo(Shop::class, 'shop_id', 'id');
        }
        
       
}

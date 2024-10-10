<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MembershipCard extends Model
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

         
        

        private function prepare_data(Request $request, MembershipCard $membershipCard = null): array
        {
            if ($membershipCard) {
                $data['membershipCard'] = [
                    'card_no'                  => $request->input('card_no') ?? $membershipCard->card_no,
                    'status'                   => $request->input('status')?? $membershipCard->status,
                    'shop_id'                  => $request->input('shop_id')?? $membershipCard->shop_id,
                    'membership_card_type_id'  => $request->input('membership_card_type_id')?? $membershipCard->membership_card_type_id,
                    'user_id'                   => $request->input('user_id')??Auth::user()->id,
                    // 'user_id' => $request->input('user_id'),
            
                ];
            } else {
                $data['membershipCard'] = [
                    'card_no'                  => $request->input('card_no'),
                    'status'                   => $request->input('status') ?? self::STATUS_INACTIVE,
                    'shop_id'                  => $request->input('shop_id'),
                    'membership_card_type_id'  => $request->input('membership_card_type_id'),
                    // 'user_id'                  => Auth::user()->id,
                    'user_id'                  => $request->input('user_id')??Auth::user()->id,
                    // 'user_id'        => $request->input('user_id'),
                    
                ];
            }

            return $data;
        }
        
        final public function get_membershipCard(Request $request)
        {
            $query = self::query();
            // $query = self::all();
            if($request->input('shop_id')){
                $query->where('shop_id', $request->input('shop_id'));
            }
            if ($request->input('card_no')) {
                $query->where('card_no', 'like', '%' . $request->input('card_no') . '%');
            }
            if ($request->input('membership_card_type_id')) {
                $query->where('membership_card_type_id', $request->input('membership_card_type_id'));
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

        public function store_membershipCard(Request $request){
            $data           = $this->prepare_data($request);
            $membershipCard = self::query()->create($data['membershipCard']);
            return $membershipCard;

        }

        public function update_membershipCard(Request $request, MembershipCard $membershipCard)
        {
            $data = $this->prepare_data($request, $membershipCard);
            $membershipCard->update($data['membershipCard']);

            return true;
        }

        public function delete_membershipCard(Model|MembershipCard $membershipCard)
        {
            return $membershipCard->delete();
        }

        /**
         * @return BelongsTo
         */
      
        public function membershipCardType(): BelongsTo
         {
             return $this->belongsTo(MembershipCardType::class);
         }

         

        public function get_membershipcardno(){
            return self::query()->where('status',self::STATUS_ACTIVE)->pluck('card_no','id' );  
        }

        public function get_shop():BelongsTo{
            return $this->belongsTo(Shop::class,'shop_id','id');
        }

        public function get_membershipcard_No_for_customer(){
            return self::query()
            ->where('status',self::STATUS_ACTIVE)
            ->get()
            ->groupBy('shop_id') ;
        }


}

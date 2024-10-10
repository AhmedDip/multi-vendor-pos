<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerGroup extends Model
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

        private function prepare_data(Request $request, CustomerGroup $customerGroup = null): array
        {
            if ($customerGroup) {
                $data['customerGroup'] = [
                    'name'            => $request->input('name') ?? $customerGroup->name,
                    'status'          => $request->input('status')?? $customerGroup->status,
                    'shop_id'         => $request->input('shop_id')?? $customerGroup->shop_id,
                ];
            } else {
                $data['customerGroup'] = [
                    'name'        => $request->input('name'),
                    'status'      => $request->input('status') ?? self::STATUS_INACTIVE,
                    'shop_id'     => $request->input('shop_id'),
                    
                ];
            }

            return $data;
        }
        final public function get_customerGroup(Request $request)
        {
            $query = self::query();
            // $query = self::all();
            if($request->input('shop_id')){
                $query->where('shop_id', $request->input('shop_id'));
            }
            if ($request->input('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }
            if ($request->input('status')) {
                $query->where('status', $request->input('status'));
            }
            if ($request->input('order_by_column')) {
                $query->orderBy($request->input('order_by_column'),$request->input('order_by')?? 'DESC');
            }
            // return $query;
            return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
        }

        public function store_customerGroup(Request $request){
            $data          = $this->prepare_data($request);
            $customerGroup = self::query()->create($data['customerGroup']);

            return $customerGroup;

        }

        public function update_customerGroup(Request $request, CustomerGroup $customerGroup)
        {
            $data = $this->prepare_data($request, $customerGroup);
            $customerGroup->update($data['customerGroup']);;

            return true;
        }

        public function delete_customerGroup(Model|CustomerGroup $customerGroup)
        {
            return $customerGroup->delete();
        }

        public function get_shop():BelongsTo{

            return $this->belongsTo(Shop::class, 'shop_id','id');
        }

}

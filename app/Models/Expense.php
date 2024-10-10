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

class Expense extends Model
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

    private function prepare_data(Request $request, Expense $expense = null): array
    {
        if ($expense) {
            $data['expense'] = [
                'purpose'    => $request->input('purpose') ?? $expense->purpose,
                'amount'     => $request->input('amount') ?? $expense->amount,
                'date'       => $request->input('date') ?? $expense->date,
                'shop_id'    => $request->input('shop_id') ?? $expense->shop_id,
                'user_id'    => $request->input('user_id') ?? Auth::user()->id,
                // 'user_id'        => $request->input('user_id'),
                'status'     => $request->input('status') ?? self::STATUS_ACTIVE,
            ];
        } else {
            $data['expense'] = [
                'purpose'   => $request->input('purpose'),
                'amount'    => $request->input('amount'),
                'date'      => $request->input('date'),
                'shop_id'   => $request->input('shop_id'),
                'user_id'   => $request->input('user_id') ?? Auth::user()->id,
                // 'user_id'        => $request->input('user_id'),
                'status'    => $request->input('status') ?? self::STATUS_ACTIVE,
            ];
        }

        return $data;
    }

    final public function get_expense(Request $request)
    {
        // dd($request->all());
        $query = self::query();

        // $query = self::all();
        if ($request->input('shop_id')) {
            $query->where('shop_id', $request->input('shop_id'));
        }
        if ($request->input('purpose')) {
            $query->where('purpose', 'like', '%' . $request->input('purpose') . '%');
        }
        if ($request->input('amount')) {
            $query->where('amount', $request->input('amount'));
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

    public function store_expense(Request $request)
    {
        $data                = $this->prepare_data($request);
        $expense = self::query()->create($data['expense']);
        return $expense;
    }

    public function update_expense(Request $request, Expense $expense)
    {
        $data = $this->prepare_data($request, $expense);
        $expense->update($data['expense']);

        return true;
    }

    public function delete_expense(Model|Expense $expense)
    {
        return $expense->delete();
    }

    public function get_shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }
    // public function get_user():BelongsTo{
    //     return $this->belongsTo(User::class,'user_id','id');
    // }

    public function total_expense($shop_id = null){
        if($shop_id == null){
            return self::where('status',self::STATUS_ACTIVE)->count();
        }
        else{
            return self::where('status',self::STATUS_ACTIVE)->where('shop_id',$shop_id)->count();
        }
    }
}

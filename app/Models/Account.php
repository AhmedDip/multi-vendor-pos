<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
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

        public static function updateOpeningBalance(int $shopId, string $date, float $openingBalance): Account
        {
            return self::updateOrCreate(
                ['shop_id' => $shopId, 'date' => $date],
                ['opening_balance' => $openingBalance]
            );
        }

        public static function getOpeningBalance(int $shopId, string $date): float
        {
            $account = self::where('shop_id', $shopId)
                ->whereDate('date', $date)
                ->first();
    
            return $account ? $account->opening_balance : 0;
        }

        public function calculateAndUpdateOpeningBalance(int $shopId)
        {
            DB::beginTransaction();
            try {
                $yesterday = Carbon::yesterday()->toDateString();
                $today     = Carbon::today()->toDateString();
        
                // dd($yesterday, $today);
        
                $closingBalance = self::query()->where('shop_id', $shopId)
                    ->whereDate('date', $yesterday)
                    ->first();
        
                if ($closingBalance) {
                    $totalIncome = Order::query()->where('shop_id', $shopId)
                        ->whereDate('order_date', $yesterday)
                        ->sum('total_amount');

                    $totalExpenses = Expense::query()->where('shop_id', $shopId)->whereDate('date', $yesterday)->sum('amount');


                    $totalDiscounts  = Order::query()->where('shop_id', $shopId)
                        ->whereDate('order_date', $yesterday)
                        ->sum('discount_amount');

                    
                    $closingBalanceAmount = $closingBalance->opening_balance
                        + $totalIncome - $totalExpenses - $totalDiscounts;
        
                    $account = self::updateOpeningBalance($shopId, $today, $closingBalanceAmount);
        
                    Log::info('calculateAndUpdateOpeningBalance - Closing Balance Amount:', ['closingBalanceAmount' => $closingBalanceAmount]);
        
                    DB::commit();
                    return $account;
                }
        
                Log::warning('calculateAndUpdateOpeningBalance - No closing balance found for yesterday:', ['yesterday' => $yesterday]);
        
                DB::rollback();
                return null;
            } catch (\Throwable $throwable) {
                DB::rollback();
                Log::error('BALANCE_UPDATED_FAILED', ['error' => $throwable->getMessage()]);
                throw $throwable;
            }
        }
        
}

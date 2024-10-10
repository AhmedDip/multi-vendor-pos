<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Transaction;

class AccountApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;
    public function add_balance(Request $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $today = Carbon::today()->toDateString();

            $previousAccount = Account::where('shop_id', $request->shop_id)
                ->whereDate('date', $today)
                ->first();

            $openingBalance    = $previousAccount ? $previousAccount->opening_balance : 0;
            $newOpeningBalance = $openingBalance + $request->opening_balance;

            $balance = Account::updateOpeningBalance(
                $request->shop_id,
                $today,
                $newOpeningBalance
            );

            $this->data = $balance;
            $this->status_message = 'Balance added successfully';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('BALANCE_ADDED_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function getBalanceReport(Request $request)
    {
        try {
            // $request->merge(['shop_id' => $request->header('shop-id')]);

            $today = Carbon::today()->toDateString();

            $account = Account::where('shop_id', $request->shop_id)
                ->whereDate('date', $today)
                ->first();

            if (!$account) {
                (new Account)->calculateAndUpdateOpeningBalance($request->shop_id);
            }

            $shopId = $request->shop_id;
            $filter = $request->input('filter', 'daily');

            switch ($filter) {
                case 'weekly':
                    $startDate = Carbon::now()->startOfWeek();
                    $endDate = Carbon::now()->endOfWeek();
                    break;
                case 'monthly':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'date_range':
                    $startDate = Carbon::parse($request->input('start_date'))->startOfDay();
                    $endDate = Carbon::parse($request->input('end_date'))->endOfDay();
                    break;
                case 'daily':
                default:
                    $startDate = Carbon::today()->startOfDay();
                    $endDate = Carbon::today()->endOfDay();
                    break;
            }

            $openingBalance = Account::where('shop_id', $shopId)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('opening_balance');

            $totalIncome = Order::where('shop_id', $shopId)
                ->whereBetween('order_date', [$startDate, $endDate])
                ->sum('total_amount');

            $totalExpenses = Expense::where('shop_id', $shopId)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

        
            $totalDiscounts = Order::where('shop_id', $shopId)
                ->whereBetween('order_date', [$startDate, $endDate])
                ->sum('discount_amount');
            
            $closingBalance = $openingBalance + $totalIncome - $totalExpenses - $totalDiscounts;

            $this->data = [
                'opening_balance' => $openingBalance,
                'total_income'    => $totalIncome,
                'total_expenses'  => $totalExpenses,
                'total_discounts' => $totalDiscounts,
                'closing_balance' => $closingBalance,
            ];

            $this->status_message = 'Balance report fetched successfully';

            DB::commit();
        } catch (Throwable $throwable) {
            app_error_log('BALANCE_REPORT_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

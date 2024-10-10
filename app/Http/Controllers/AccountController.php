<?php

namespace App\Http\Controllers;

use Throwable;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Account;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{

    public function addBalanceView(Request $request)
    {
        $cms_content = [
            'module'       => __('Account'),
            // 'module_url'   => route('account.index'),
            'active_title' => __('Add Balance'),
            'button_type'  => 'create',
            'button_title' => __('Add Balance'),
            // 'button_url'   => route('account.calculate-balance'),
        ];

        $search      = $request->all();
        $shops       = (new Shop())->get_shops_assoc();

        return view('admin.modules.account.add-balance', compact('cms_content', 'search', 'shops'));
    }

    public function addBalance(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->merge(['shop_id' => $request->input('shop_id')]);
            $today = Carbon::today()->toDateString();

            $previousAccount = Account::where('shop_id', $request->shop_id)
                ->whereDate('date', $today)
                ->first();

            $openingBalance    = $previousAccount ? $previousAccount->opening_balance : 0;
            $newOpeningBalance = $openingBalance + $request->opening_balance;

            Account::updateOpeningBalance(
                $request->shop_id,
                $today,
                $newOpeningBalance
            );

            DB::commit();

            return redirect()->route('balance_report_data',['shop_id'=>$request->shop_id])->with('success', 'Balance added successfully');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('BALANCE_CREATE_FAILED', $throwable, 'error');
            return redirect()->back()->withErrors(['error' => 'Failed to add balance']);
        }
    }


    public function balanceReport(Request $request)
    {
        try {
            $shopId = $request->input('shop_id') ?? 0;

            $today = Carbon::today()->toDateString();
            $account = Account::where('shop_id', $shopId)
                ->whereDate('date', $today)
                ->first();
            
            //everyday this will run and update the opening balance

            if (!$account) {
                (new Account)->calculateAndUpdateOpeningBalance($shopId);
            }

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

            $openingBalance = Account::query()->where('shop_id', $shopId)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('opening_balance');

            $totalIncome = Order::query()->where('shop_id', $shopId)
                ->whereBetween('order_date', [$startDate, $endDate])
                ->sum('total_amount');

            $totalExpenses = Expense::query()->where('shop_id', $shopId)
                ->whereBetween('date', [$startDate, $endDate])
                ->sum('amount');

            // $totalDiscounts = Order::join('transactions', 'orders.id', '=', 'transactions.order_id')
            //     ->where('orders.shop_id', $shopId)
            //     ->whereBetween('transactions.created_at', [$startDate, $endDate])
            //     ->sum('transactions.discount');

            $totalDiscounts = Order::query()->where('shop_id', $shopId)
                ->whereBetween('order_date', [$startDate, $endDate])
                ->sum('discount_amount');

            $closingBalance = $openingBalance + $totalIncome - $totalExpenses - $totalDiscounts;

            $data = [
                'opening_balance' => $openingBalance,
                'total_income'    => $totalIncome,
                'total_expenses'  => $totalExpenses,
                'total_discounts' => $totalDiscounts,
                'closing_balance' => $closingBalance,
            ];

            $shops = (new Shop())->get_shops_assoc();
            $search      = $request->all();
        } catch (Throwable $throwable) {
            app_error_log('BALANCE_REPORT_FAILED', $throwable, 'error');
            return redirect()->back()->withErrors(['error' => 'Failed to fetch balance report']);
        }
        return view('admin.modules.account.balance-report', compact('data', 'shops', 'shopId', 'filter', 'search'));
    }
}

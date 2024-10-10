<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Expense;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport(Request $request)
    {
        $cms_content = [
            'module'       => __('Sales Report'),
            'module_url'   => route('sales-report'),
            'active_title' =>  __('Sales Report'),
            'button_type'  => 'list',
            'button_title' => __('Back'),
            'button_url'   => route('sales-report'),
        ];


        $shops              = (new Shop())->get_shops_assoc();

        $shopId = $request->input('shop_id');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : now()->endOfDay();


        $search  = $request->all();


        $query = Order::with('items.product', 'customer')->where('status', Order::STATUS_COMPLETED);

        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
    
        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate]);
        }
    
        $sales = $query->get();
    
        $totalSalesAmount = $sales->sum('total_payable_amount');

        $totalProductsSold = $sales->map(function ($order) {
            return $order->items->sum('quantity');
        })->sum();
    
       
        return view('admin.modules.report.sales',
            compact('cms_content', 'sales', 'shops', 'shopId', 'startDate', 'endDate', 'totalSalesAmount', 'totalProductsSold', 'search'));
       
        
    }

    public function profitLossReport(Request $request)
    {
        $cms_content = [
            'module'       => __('Profit Loss Report'),
            'module_url'   => route('profit-loss-report'),
            'active_title' => __('Profit Loss Report'),
            'button_type'  => 'list',
            'button_title' => __('Back'),
            'button_url'   => route('profit-loss-report'),
        ];
    
        $shops     = (new Shop())->get_shops_assoc();
        $shopId    = $request->input('shop_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));
    
        $query = Order::query()->where('status', Order::STATUS_COMPLETED);
    
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
    
        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate]);
        }
    
        $totalIncome  = $query->sum('total_payable_amount');
        $expenseQuery = Expense::query();
    
        if ($shopId) {
            $expenseQuery->where('shop_id', $shopId);
        }
    
        if ($startDate && $endDate) {
            $expenseQuery->whereBetween('date', [$startDate, $endDate]);
        }
    
        $totalExpenses = $expenseQuery->sum('amount');
        $profitOrLoss  = $totalIncome - $totalExpenses;
        $search        = $request->all();
    
        return view('admin.modules.report.profit-loss', compact('cms_content', 'shops', 'shopId', 'startDate', 'endDate', 'totalIncome', 'totalExpenses', 'profitOrLoss', 'search'));
    }

    public function topCustomerReport(Request $request)
    {
        $cms_content = [
            'module'       => __('Top Customer Report'),
            'module_url'   => route('top-customer-report'),
            'active_title' => __('Top Customer Report'),
            'button_type'  => 'list',
            'button_title' => __('Back'),
            'button_url'   => route('top-customer-report'),
        ];
    
        $shops     = (new Shop())->get_shops_assoc();
        $shopId    = $request->input('shop_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));
    
        $query = Order::query()->where('status', Order::STATUS_COMPLETED);
    
        if ($shopId) {
            $query->where('shop_id', $shopId);
        }
    
        if ($startDate && $endDate) {
            $query->whereBetween('order_date', [$startDate, $endDate]);
        }
    
        $topCustomers = $query->with('customer')->get()->groupBy('customer_id')->map(function ($orders) {
            return [
                'customer' => $orders->first()->customer,
                'total_amount' => $orders->sum('total_payable_amount'),
                'total_orders' => $orders->count(),
            ];
        })->sortByDesc('total_amount')->values();
    
        $search = $request->all();
    
        return view('admin.modules.report.top-customer', compact('cms_content', 'shops', 'shopId', 'startDate', 'endDate', 'topCustomers', 'search'));
    }
    
}

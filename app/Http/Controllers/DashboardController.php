<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * @return View
     */

    //  public function index(Request $request)
    // {
    //     $shopId = $request->input('shop_id', null); // Example: Get shop_id from request
    //     $duration = $request->input('duration', 'today'); // Default duration

    //     // Initialize variables to store data
    //     $totalOrder = 0;
    //     $totalCustomer = 0;
    //     $totalProduct = 0;
    //     $totalTopProduct = 0;
    //     $totalAppointment = 0;
    //     $totalExpense = 0;
    //     $totalNetAmount = 0;
    //     $totalSold = 0;

    //     // Logic to fetch data based on duration
    //     switch ($duration) {
    //         case 'last-7-days':
    //             $startDate = Carbon::now()->subDays(7)->startOfDay();
    //             $endDate = Carbon::now()->endOfDay();
    //             break;
    //         case 'this-month':
    //             $startDate = Carbon::now()->startOfMonth();
    //             $endDate = Carbon::now()->endOfMonth();
    //             break;
    //         case 'last-month':
    //             $startDate = Carbon::now()->subMonth()->startOfMonth();
    //             $endDate = Carbon::now()->subMonth()->endOfMonth();
    //             break;
    //         case 'this-year':
    //             $startDate = Carbon::now()->startOfYear();
    //             $endDate = Carbon::now()->endOfYear();
    //             break;
    //         case 'last-year':
    //             $startDate = Carbon::now()->subYear()->startOfYear();
    //             $endDate = Carbon::now()->subYear()->endOfYear();
    //             break;
    //         default: // Default case for 'today' or custom handling
    //             $startDate = Carbon::now()->startOfDay();
    //             $endDate = Carbon::now()->endOfDay();
    //             break;
    //     }

    //     // Fetch total orders count for the selected duration
    //     $totalOrder = Order::whereBetween('created_at', [$startDate, $endDate])
    //                         ->whereNotNull('customer_id') // Example condition for orders with customers
    //                         ->count();

    //     // Fetch total customers count for the selected duration
    //     $totalCustomer = Customer::whereBetween('created_at', [$startDate, $endDate])
    //                             ->count();

    //     // Fetch total products count for the selected duration
    //     $totalProduct = Product::whereBetween('created_at', [$startDate, $endDate])
    //                             ->count();

    //     // Fetch total appointments count for the selected duration
    //     // Example: Replace with your appointment model and conditions
    //     $totalAppointment = Appointment::whereBetween('created_at', [$startDate, $endDate])
    //                                     ->count();

    //     // Fetch total expenses for the selected duration
    //     // Example: Replace with your expense model and conditions
    //     $totalExpense = Expense::whereBetween('created_at', [$startDate, $endDate])
    //                             ->sum('amount');

    //     // Fetch total net amount for the selected duration
    //     // Example: Replace with your order model and conditions
    //     $totalNetAmount = Order::whereBetween('created_at', [$startDate, $endDate])
    //                             ->sum('total_amount');

    //     // Fetch total sold products count for the selected duration
    //     // Example: Replace with your order model and conditions
    //     // $totalSold = Order::whereBetween('created_at', [$startDate, $endDate])
    //                         // ->sum('total_sold');
    //     // $totalSold = Order::whereBetween('created_at', [$startDate, $endDate])
    //     //           ->sum(function ($order) {
    //     //               return $order->total_sold();
    //     //           });

    //     // Fetch top products count for the selected duration
    //     // Example: Replace with your product model and conditions
    //     // $totalTopProduct = Product::whereBetween('created_at', [$startDate, $endDate])
    //     //                             ->orderByDesc('sold_count')
    //     //                             ->first();

    //     // Return data as JSON or view depending on your application structure
    //     return response()->json([
    //         'shop_id' => $shopId,
    //         'duration' => $duration,
    //         'totalOrder' => $totalOrder,
    //         'totalCustomer' => $totalCustomer,
    //         'totalProduct' => $totalProduct,
    //         'totalTopProduct' => $totalTopProduct,
    //         'totalAppointment' => $totalAppointment,
    //         'totalExpense' => $totalExpense,
    //         'totalNetAmount' => $totalNetAmount,
    //         'totalSold' => $totalSold,
    //     ]);
    // }

    final public function index(): View
    {
        $cms_content = [
            'module'       => __('Report'), // page title and breadcrumb's first element title
            'module_url'   => route('dashboard'),
            'active_title' => __('Sales Report'), // breadcrumb's active title
            'button_type'  => 'delete', // list|create|edit, page right button
            // 'button_title' => __('Report'),
            'button_url'   => route('dashboard'),
        ];


        $totalOrder       = (new Order())->total_order();
        // $totalDiscount    = (new Transaction())->total_discount();
        $totalCustomer    = (new Customer())->total_customer();
        $totalAppointment = (new Appointment)->total_appointment();
        $totalProduct     = (new Product())->total_product();
        $totalTopProduct  = (new Product())->total_top_product();
        $totalExpense     = (new Expense())->total_expense();
        $totalDiscount    = (new Order())->total_discount();
        $totalNetAmount   = (new Order())->total_net_amount();
        $totalSold        = (new Order())->total_sold();
        
        $shops            = (new Shop())->get_shops_assoc();
        $shop             = (new Shop())->total_shop();
        

        return view('admin.modules.dashboard.index', compact('cms_content',
        'totalProduct','totalAppointment','totalCustomer',
        'totalOrder','totalTopProduct', 'totalExpense','totalDiscount',
    'totalNetAmount','shops','shop','totalSold'));
    }
    

    public function update(Request $request)
    {
        $totalOrder       = (new Order())->total_order($request->input('shop_id'));
        $totalProduct     = (new Product())->total_product($request->input('shop_id'));
        $totalTopProduct  = (new Product())->total_top_product($request->input('shop_id'));

        $totalCustomer    = (new Customer())->total_customer($request->input('shop_id'));
        $totalExpense     = (new Expense())->total_expense($request->input('shop_id'));
        // $brand            = (new Brand())->total_brand($request->input('shop_id'));

        $totalAppointment = (new Appointment())->total_appointment($request->input('shop_id'));

        $totalDiscount    = (new Order())->total_discount($request->input('shop_id'));
        $totalNetAmount   = (new Order())->total_net_amount($request->input('shop_id'));
        $totalSold        = (new Order())->total_sold($request->input('shop_id'));

        return response()->json([
            'totalOrder'       => $totalOrder,
            'totalProduct'     => $totalProduct,
            'totalTopProduct'  => $totalTopProduct,
            'totalCustomer'    => $totalCustomer,
            'totalAppointment' => $totalAppointment,
            'totalExpense'     => $totalExpense,
            'totalNetAmount'   => $totalNetAmount,
            'totalDiscount'    => $totalDiscount,
            'totalSold'        => $totalSold,
        ]);
    }


    final public function switchTheme(Request $request): RedirectResponse
    {
        set_theme($request->input('theme_id', 1));
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    final public function switchLanguage(Request $request): RedirectResponse
    {
        $language = $request->input('locale', 'en');
        app()->setLocale($language);
        Cache::put('language', $language);
        return redirect()->back();
    }

    public function getTopCustomers(Request $request, $shop_id = null)
    {
        $shop_id = $request->query('shop_id');

        if ($shop_id == null) {
            $customers = Customer::withCount('orders')
                ->whereNotNull('name') 
                // ->where('status', Customer::STATUS_ACTIVE)
                ->having('orders_count', '>' ,5)
                ->orderByDesc('orders_count')
                ->take(5)
                ->get();
        } else {
            $customers = Customer::withCount(['orders' => function ($query) use ($shop_id) {
                    $query->where('shop_id', $shop_id);
                }])
                ->whereNotNull('name') 
                // ->where('status', Customer::STATUS_ACTIVE)
                ->having('orders_count', '>' , 5)
                ->orderByDesc('orders_count')
                ->take(5)
                ->get();
        }
    
        // Filter out entries where name is null
        $filteredCustomers = $customers->filter(function ($customer) {
            return $customer->name !== null;
        });
    
        return response()->json([
            'orderCount'       => $filteredCustomers->pluck('orders_count')->toArray(),
            'customerNames'    => $filteredCustomers->pluck('name')->toArray(),
        ]);
    }

    public function getTopProducts(Request $request, $shop_id = null)
    {
        $shop_id = $request->query('shop_id');

        if ($shop_id == null) {
            $products = Product::whereNotNull('name') 
                ->where('status', Product::STATUS_ACTIVE)
                ->where('sold','>',5)
                ->orderByDesc('sold')
                ->take(5)
                ->get();
        } else {
            $products = Product::where('shop_id',$shop_id)
                ->whereNotNull('name') 
                ->where('status', Product::STATUS_ACTIVE)
                ->where('sold','>',5)
                ->orderByDesc('sold')
                ->take(5)
                ->get();
        }
    
        // Filter out entries where name is null
        $filteredProducts = $products->filter(function ($product) {
            return $product->name !== null;
        });
    
        return response()->json([
            'totalSold'       => $filteredProducts->pluck('sold')->toArray(),
            'productNames'    => $filteredProducts->pluck('name')->toArray(),
        ]);
    }

    public function getDatewiseSales(Request $request, $shop_id = null)
    {
        $shop_id = $request->query('shop_id');

        if ($shop_id == null) {
            $orders = Order::SelectRaw('DATE(order_date) as order_date ,sum(transactions.total_payable_amount) as total_amount')
                ->leftJoin('transactions','orders.id', '=' , 'transactions.order_id')
                ->whereNotNull('order_date') 
                ->groupBy('order_date')
                ->orderByDesc('order_date')
                // ->take(5)
                ->get();
        } else {
            $orders = Order::SelectRaw('DATE(order_date) as order_date ,sum(transactions.total_payable_amount) as total_amount')
                ->leftJoin('transactions','orders.id', '=' , 'transactions.order_id')
                ->where('shop_id',$shop_id)
                ->whereNotNull('order_date') 
                ->groupBy('order_date')
                ->orderByDesc('order_date')
                // ->take(5)
                ->get();
        }
    
        // Filter out entries where name is null
        $filteredOrders = $orders->filter(function ($order) {
            return $order->order_date !== null;
        });
    
        return response()->json([
            'totalSale'  => $filteredOrders->pluck('total_amount')->toArray(),
            'dateValue'  => $filteredOrders->pluck('order_date')->map(function ($date) {
                return Carbon::parse($date)->format('j M');
            })->toArray(),

        ]);
    }

}

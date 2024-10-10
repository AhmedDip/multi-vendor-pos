<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Appointment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $cms_content = [
            'module'       => __('Home'),
            'module_url'   => route('dashboard'),
            'active_title' => __('Dashboard'),
        ];

        $duration = $request->input('duration') ?? 'yearly';
        $shop_id  = $request->input('shop_id');
        if ($duration) {
            $end_date   = Carbon::now()->endOfDay();
            $start_date = match ($duration) {
                'weekly'  => Carbon::now()->subDays(7)->startOfDay(),
                'monthly' => Carbon::now()->subDays(30)->startOfDay(),
                'yearly'  => Carbon::now()->subYear()->startOfDay(),
                'today'   => Carbon::now()->startOfDay(),
            };
        } else {
            $start_date = null;
            $end_date   = null;
        }

        $total_order             = (new Order())->total_order($shop_id, $start_date, $end_date);
        $total_sold              = (new OrderItem())->total_sold($shop_id, $start_date, $end_date);
        $total_sales             = (new Order())->total_net_amount($shop_id, $start_date, $end_date);
        $total_discount          = (new Order())->total_discount($shop_id, $start_date, $end_date);
        $total_customers         = (new Customer())->total_customer($shop_id, $start_date, $end_date);
        $top_customer            = (new Order())->top_customer($shop_id, $start_date, $end_date);
        $total_shop              = (new Shop())->total_shop();
        $total_sales_by_category = (new Order())->total_sales_percentage_by_category($shop_id, $start_date, $end_date);
        $monthly_sales           = (new Order())->monthly_sales($shop_id);
        $sales_data_format       = (new Order())->sales_data($shop_id, $start_date, $end_date);
        $sales_data              = $this->formatSalesData($sales_data_format, $duration);
        $monthly_orders          = (new Order())->monthly_orders($shop_id, $start_date, $end_date);
        $top_selling_products    = (new OrderItem())->top_selling_product($shop_id, $start_date, $end_date);
        $shops                   = (new Shop())->get_shops_assoc();

        // dd($top_customer);


        return view('admin.modules.dashboard2.index', compact('cms_content', 'total_order', 'total_sold', 'total_sales', 'total_discount', 'total_customers', 'top_customer', 'total_sales_by_category', 'monthly_sales', 'monthly_orders', 'top_selling_products', 'shops', 'total_shop', 'sales_data', 'duration'));
    }

    public function formatSalesData($sales_data, $duration)
    {
        $result = [];

        switch ($duration) {
            case 'weekly':
                $daysOfWeek = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
                $weeklyData = array_fill_keys($daysOfWeek, 0);

                foreach ($sales_data as $data) {
                    $dayOfWeek = strtolower(Carbon::parse($data->date)->format('D'));
                    $weeklyData[$dayOfWeek] += $data->total_sales;
                }

                foreach ($weeklyData as $day => $total) {
                    $result[] = ['key' => $day, 'value' => $total];
                }
                break;

            case 'monthly':
                $daysInMonth = Carbon::now()->daysInMonth;
                $monthlyData = array_fill(1, $daysInMonth, 0);

                foreach ($sales_data as $data) {
                    $dayOfMonth = (int) Carbon::parse($data->date)->format('d');
                    $monthlyData[$dayOfMonth] += $data->total_sales;
                }

                foreach ($monthlyData as $day => $total) {
                    $result[] = ['key' => $day, 'value' => $total];
                }
                break;

            case 'yearly':
                $monthsOfYear = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $yearlyData   = array_fill_keys($monthsOfYear, 0);

                foreach ($sales_data as $data) {
                    $monthOfYear = Carbon::parse($data->date)->format('M');
                    $yearlyData[$monthOfYear] += $data->total_sales;
                }

                foreach ($yearlyData as $month => $total) {
                    $result[] = ['key' => $month, 'value' => $total];
                }
                break;

            case 'today':
                $hoursOfDay = range(0, 23);
                $todayData  = array_fill_keys($hoursOfDay, 0);

                foreach ($sales_data as $data) {
                    $hourOfDay = (int) Carbon::parse($data->hour)->format('H');
                    $todayData[$hourOfDay] += $data->total_sales;
                }

                foreach ($todayData as $hour => $total) {
                    $result[] = ['key' => $hour, 'value' => $total];
                }
                break;
        }

        return $result;
    }

    public function getSalesData(Request $request)
    {
        $shop_id  = $request->input('shop_id') ?? null;
        $duration = $request->input('duration') ?? 'weekly';

        $end_date = Carbon::now()->endOfDay();
        $start_date = match ($duration) {
            'weekly'  => Carbon::now()->subDays(7)->startOfDay(),
            'monthly' => Carbon::now()->subDays(30)->startOfDay(),
            'yearly'  => Carbon::now()->subYear()->startOfDay(),
            'today'   => Carbon::now()->startOfDay(),
            default   => Carbon::now()->subDays(7)->startOfDay(),
        };

        $sales_data_format = (new Order())->sales_data($shop_id, $start_date, $end_date);
        $sales_data = $this->formatSalesData($sales_data_format, $duration);

        return response()->json($sales_data);
    }

    public function getShopData(Request $request)
    {
        $shop_id  = $request->input('shop_id') ?? null;
        $duration = $request->input('duration') ?? 'weekly';

        $end_date   = Carbon::now()->endOfDay();
        $start_date = match ($duration) {
            'weekly'  => Carbon::now()->subDays(7)->startOfDay(),
            'monthly' => Carbon::now()->subDays(30)->startOfDay(),
            'yearly'  => Carbon::now()->subYear()->startOfDay(),
            'today'   => Carbon::now()->startOfDay(),
            default   => Carbon::now()->subDays(7)->startOfDay(),
        };

        $total_order             = (new Order())->total_order($shop_id, $start_date, $end_date);
        $total_sold              = (new OrderItem())->total_sold($shop_id, $start_date, $end_date);
        $total_sales             = (new Order())->total_net_amount($shop_id, $start_date, $end_date);
        $total_discount          = (new Order())->total_discount($shop_id, $start_date, $end_date);
        $total_customers         = (new Customer())->total_customer($shop_id, $start_date, $end_date);
        $top_customer            = (new Order())->top_customer($shop_id, $start_date, $end_date);
        $monthly_sales           = (new Order())->monthly_sales($shop_id);
        $total_sales_by_category = (new Order())->total_sales_percentage_by_category($shop_id, $start_date, $end_date);
        $sales_data_format       = (new Order())->sales_data($shop_id, $start_date, $end_date);
        $sales_data              = $this->formatSalesData($sales_data_format, $duration);
        $top_selling_products    = (new OrderItem())->top_selling_product($shop_id, $start_date, $end_date);
        
        return response()->json([
            'total_order'             => $total_order,
            'total_sold'              => $total_sold,
            'total_sales'             => $total_sales,
            'total_discount'          => $total_discount,
            'total_customers'         => $total_customers,
            'top_customer'            => $top_customer,
            'monthly_sales'           => $monthly_sales,
            'total_sales_by_category' => $total_sales_by_category,
            'sales_data'              => $sales_data,
            'top_selling_products'    => $top_selling_products
        ]);
    }
}

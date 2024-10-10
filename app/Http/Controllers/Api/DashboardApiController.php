<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\TopSellingProductResource;
use App\Manager\AccessControl\AccessControlTrait;

class DashboardApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'dashboard';
    /**
     * Display a listing of the resource.
     */
   

    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);

            $duration = $request->input('duration');
            if ($duration) {
                $end_date = Carbon::now()->endOfDay();
                $start_date = match ($duration) {
                    'weekly'  => Carbon::now()->subDays(7)->startOfDay(),
                    'monthly' => Carbon::now()->subDays(30)->startOfDay(),
                    'yearly'  => Carbon::now()->subYear()->startOfDay(),
                    'today'   => Carbon::now()->startOfDay(),
                };
            } else {
                $start_date = null;
                $end_date = null;
            }


            $order                   = (new Order())->total_order($request->shop_id, $start_date, $end_date);
            $total_sold              = (new OrderItem())->total_sold($request->shop_id, $start_date, $end_date);
            $total_sales             = (new Order())->total_net_amount($request->shop_id, $start_date, $end_date);
            $total_discount          = (new Order())->total_discount($request->shop_id, $start_date, $end_date);
            $total_customers         = (new Customer())->total_customer($request->shop_id, $start_date, $end_date);
            $top_customer            = (new Order())->top_customer($request->shop_id, $start_date, $end_date);
            $total_sales_by_category = (new Order())->total_sales_percentage_by_category($request->shop_id, $start_date, $end_date);
            $monthly_sales           = (new Order())->monthly_sales($request->shop_id);
            $sales_data_format       = (new Order())->sales_data($request->shop_id, $start_date, $end_date);
            $sales_data              = $this->formatSalesData($sales_data_format, $duration);
            $monthly_orders          = (new Order())->monthly_orders($request->shop_id);
            $top_selling_product     = (new OrderItem())->top_selling_product($request->shop_id, $start_date, $end_date);

            $this->data = [
                'total_order'             => $order,
                'total_sold'              => $total_sold,
                'total_sales'             => $total_sales,
                'total_discount'          => $total_discount,
                'total_customer'          => $total_customers,
                'top_customer'            => $top_customer,
                'monthly_sales'           => $monthly_sales,
                'sales_data'              => $sales_data,
                'monthly_orders'          => $monthly_orders,
                'total_sales_by_category' => $total_sales_by_category,
                'top_selling_product'     => count($top_selling_product) > 0 ? TopSellingProductResource::collection($top_selling_product) : [],
            ];

            $this->status_message = 'Dashboard Api Data Fetched Successfully';

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('data_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }

        return $this->commonApiResponse();
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function formatSalesData($sales_data, $duration)
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
                $yearlyData = array_fill_keys($monthsOfYear, 0);

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
                $todayData = array_fill_keys($hoursOfDay, 0);

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
}

<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\SalesReportResource;
use App\Manager\AccessControl\AccessControlTrait;

class ReportApiController extends Controller
{

    use CommonResponse, AppActivityLog, AccessControlTrait;
    public function get_sales_report(Request $request)
    {
        try {
            DB::beginTransaction();
            $order                = new Order();
            $sales                = $order->get_sales_report($request);
            $pagination           = PaginationHelper::generatePaginationData($sales);
            $total_sold           = $order->get_total_sold($request);
            $total_payable_amount = $order->get_total_payable_amount($request);
            $this->status_message = 'Sales report Data Fetched Successfully.';
            $this->data           = [
                'sales_data'           => SalesReportResource::collection($sales),
                'pagination'           => $pagination,
                'total_sold'           => $total_sold,
                'total_payable_amount' => $total_payable_amount,

            ];
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_SHOW_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function get_profit_loss_report(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new Order();
            $profit_loss = $order->get_profit_loss_report($request);
            $this->status_message = 'Profit loss report fetched successfully.';
            $this->data           = $profit_loss;
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_SHOW_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function get_top_customer_report(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new Order();
            $top_customers = $order->get_top_customers($request);
            $this->status_message = 'Top customers report fetched successfully.';
            $this->data           = $top_customers;
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_SHOW_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Http\Resources\OrderPdfResource;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\SalesReportResource;
use App\Http\Resources\OrderDetailsResource;
use App\Http\Resources\OrderDetailApiResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Http\Resources\OrderTransactionDetailsResource;

class OrderApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $orders = (new Order())->get_orders($request);
            $this->status_message = 'Orders fetched successfully.';
            $this->data           = OrderDetailApiResource::collection($orders);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $original = $request->all();
            $order   = (new Order())->store_order($request);
            $changed = $order->getChanges();

            $pdf_data = (new Order())->get_order_pdf_data($order->invoice_number);

            self::activityLog($request, $original, $changed, $order);
            $this->status_message = 'Order Has Been Created Successfully.';

            $this->data         = (new OrderPdfResource($pdf_data));
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_STORE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        try {
            DB::beginTransaction();
            $this->status_message = 'Order details.';

            $order->load('items', 'customer', 'transactions', 'previous_orders');

            $this->data           = new OrderDetailsResource($order);
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
    public function update(Request $request, Order $order)
    {
        try {
            DB::beginTransaction();
            $original = $request->all();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $order->update_order($request, $order);
            $changed = $order->getChanges();
            $pdf_data = (new Order())->get_order_pdf_data($order->invoice_number);
            self::activityLog($request, $original, $changed, $order);
            $this->status_message = 'Order has been updated successfully.';

            $this->data         = (new OrderPdfResource($pdf_data));
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_UPDATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }

    public function transaction(Request $request, $invoice_no)
    {
        try {
            DB::beginTransaction();
            $original = $request->all();
            $order = Order::where('invoice_number', $invoice_no)->firstOrFail();
            
            foreach ($request->input('transactions') as $transactionData) {
                (new Transaction())->store_transaction(new Request($transactionData), $order);
            }
            $this->status_message = 'Transaction has been added successfully.';
            $changed = $order->getChanges();
            $pdf_data = (new Order())->get_order_pdf_data($order->invoice_number);
            self::activityLog($request, $original, $changed, $order);
            $this->status_message = 'Order has been updated successfully.';

            $this->data         = (new OrderPdfResource($pdf_data));
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_UPDATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order, Request $request)
    {
        try {
            DB::beginTransaction();
            $original = $order->getOriginal();
            $order->delete_order($order);
            $changed = $order->getChanges();
            self::activityLog($request, $original, $changed, $order);
            $this->status_message = 'Order has been deleted successfully.';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_DELETE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }

    public function get_transaction_data(Request $request, $invoice_no)
    {
        try {
            DB::beginTransaction();
            $order = (new Order())->get_order_by_invoice_no($invoice_no);
            $dependancy_data = $order->get_dependancy_data($request);
            $this->status_message = 'Order details.';
            $this->data           = [
                'transaction_data' => new OrderTransactionDetailsResource($order),
                'dependancy_data' => $dependancy_data,
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

    // public function transaction(Request $request, $invoice_no)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $order = (new Order())->get_order_by_invoice_no($invoice_no);
    //         $original = $request->all();
    //         Transaction::prepare_transaction_data($order, $request);

    //         $order->update(['discount_amount' => $request->discount]);

    //         $changes = $order->getChanges();
    //         self::activityLog($request, $original, $changes, $order);
    //         $this->status_message = 'Order has been updated successfully.';
    //         $this->data           = new OrderTransactionDetailsResource($order);
    //         DB::commit();
    //     }
    //     catch (Throwable $throwable) {
    //         DB::rollBack();
    //         app_error_log('ORDER_UPDATE_FAILED', $throwable, 'error');
    //         $this->status_message = 'Failed! ' . $throwable->getMessage();
    //         $this->status_code = $this->status_code_failed;
    //         $this->status = false;
    //     }
    //     return $this->commonApiResponse();
    // }


    public function get_order_dependency_data(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = new Order();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $dependancy_data = $order->get_dependancy_data($request);
            $this->status_message = 'Order details.';
            $this->data           =  $dependancy_data;
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

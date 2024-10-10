<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OrderRequest;
use App\Models\Traits\AppActivityLog;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\OrderBackendRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\OrderDetailsResource;
use App\Manager\AccessControl\AccessControlTrait;

class OrderController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cms_content = [
            'module'       => __('Order'),
            'module_url'   => route('order.index'),
            'active_title' => __('Order List'),
            'button_type'  => 'create',
            'button_title' => __('Create Order'),
            'button_url'   => route('order.create'),
        ];

        $orders = (new Order())->get_orders($request);
        // dd($orders);
        $shops  = (new Shop())->get_shops_assoc();
        $orderCounts = Order::getOrderCounts();

        $columns = [
            'shop_id'             => 'Shop',
            'order_date'          => 'Order Date',
            'invoice_number'      => 'Invoice number',
            'total_payable_amount' => 'Total Amount',
            'total_paid_amount'   => 'Total Paid',
            'order_status'        => 'Order Status',
            'payment_status'      => 'Payment Status',
        ];

        $search  = $request->all();

        return view('admin.modules.order.index', compact('orders', 'shops', 'cms_content', 'columns', 'search', 'orderCounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $cms_content = [
            'module'       => __('Order'),
            'module_url'   => route('order.index'),
            'active_title' => __('Order Create'),
            'button_type'  => 'list',
            'button_title' => __('Order List'),
            'button_url'   => route('order.index'),
        ];

        $shops                = (new Shop())->get_shops_assoc();
        $products             = (new Product())->get_product_data();
        $payment_methods      = (new PaymentMethod())->get_payment_method_assoc();
        $employees            = (new User())->get_employee_data($request);
        $order                = new Order();
        $paymentMethodsByShop = PaymentMethod::getGroupedByShop();
        $appointmentId        = $request->query('appointment_id');
        $shopId               = $request->query('shop_id');
        $customer_name        = $request->query('customer_name');
        $customer_phone       = $request->query('customer_phone');
        $invoice_number       = $request->query('invoice_number');
        $productId            = DB::table('appointment_product')
                                ->where('appointment_id', $appointmentId)
                                ->value('product_id');

        // dd($payment_methods);

        return view('admin.modules.order.create', compact('products', 'cms_content', 'shops', 'payment_methods', 'employees', 'order', 'paymentMethodsByShop', 'shopId',  'productId', 'customer_name', 'customer_phone', 'invoice_number', 'productId'));
    }



    /**
     * Store a newly created resource in storage.
     */


    public function store(OrderRequest $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {

            if ($request->input('phone')) {
                $customer = Customer::query()->where('phone', $request->input('phone'))->where('shop_id', $request->input('shop_id'))->first();
            }

            if (!$customer) {
                $customer = (new Customer())->store_customer($request);
            }

       

            $order  = (new Order())->store_order_backend($request, $customer);


            $order->processOrderItemsAndStock($request);

            if ($request->has('amount') && !empty($request->input('amount'))) {
                foreach ($request->input('amount') as $index => $amount) {
                    if (!empty($amount) && !empty($request->input('payment_method_id')[$index])) {
                        $transactionData = [
                            'order_id'          => $order->id,
                            'payment_method_id' => $request->input('payment_method_id')[$index],
                            'amount'            => $amount,
                            'sender_account'    => $request->input('sender_account')[$index] ?? null,
                            'trx_id'            => $request->input('trx_id')[$index] ?? null,
                            'payment_status'    => $request->input('payment_status')[$index] ?? Order::PAYMENT_STATUS_SUCCEESS,
                        ];
            
                        $transactionRequest = new Request($transactionData);
                        (new Transaction())->store_transaction($transactionRequest, $order);
                    }
                }
            }

         
            $original = [];

            $changed = $order->getOriginal();
            // dd($changed);
            self::activityLog($request, $original,  $changed, $order);
            success_alert(__('Order Created Successfully'));
            DB::commit();
            return redirect()->route('order.index')->with('success', 'Order created successfully!');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_CREATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('order.index')->withErrors(['error' => $throwable->getMessage()]);
        }
    }





    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $cms_content = [
            'module'       => __('Order'),
            'module_url'   => route('order.index'),
            'active_title' => __('Order Show'),
            'button_type'  => 'list',
            'button_title' => __('Order List'),
            'button_url'   => route('order.index'),
        ];

        return view('admin.modules.order.show', compact('order', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $cms_content = [
            'module'       => __('Order'),
            'module_url'   => route('order.index'),
            'active_title' => __('Order Edit'),
            'button_type'  => 'list',
            'button_title' => __('Order List'),
            'button_url'   => route('order.index'),
        ];


        $shops           = (new Shop())->get_shops_assoc();
        $products        = (new Product())->get_product_data();
        $payment_methods = (new PaymentMethod())->get_payment_method_assoc();
        $orderItems      = $order->items()->with('product')->get();
        $employees       = (new User())->get_employee_data(request());
        $paymentMethodsByShop = PaymentMethod::getGroupedByShop();

        return view('admin.modules.order.edit', compact('order', 'products', 'cms_content', 'shops', 'payment_methods', 'orderItems', 'employees', 'paymentMethodsByShop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, Order $order)
    {
        DB::beginTransaction();
        try {
            $original = $order->getOriginal(); 
    
            $order->update_order_backend($request, $order, $order->customer);
    
            foreach ($request->product_id as $index => $productId) {
                $orderItem = OrderItem::where('order_id', $order->id)
                    ->where('product_id', $productId)
                    ->first();
    
                $newQuantity = $request->quantity[$index];
    
                if ($orderItem) {
                    $diff = $newQuantity - $orderItem->quantity;
    
                    $orderItem->update([
                        'quantity'    => $newQuantity,
                        'unit_price'  => $request->unit_price[$index],
                        'total_price' => $request->total_price[$index],
                        'assign_to'   => $request->assign_to[$index] ?? null,
                    ]);
    
                  
                    if ($order->status == Order::STATUS_COMPLETED ||  $order->status == Order::STATUS_PENDING) {
                        (new Product())->update_stock($productId, $diff);
                    }
    
                } else {
                    OrderItem::create([
                        'order_id'    => $order->id,
                        'product_id'  => $productId,
                        'quantity'    => $newQuantity,
                        'unit_price'  => $request->unit_price[$index],
                        'total_price' => $request->total_price[$index],
                        'assign_to'   => $request->assign_to[$index] ?? null,
                    ]);
    
                 
                    if ($order->status == Order::STATUS_COMPLETED ||  $order->status == Order::STATUS_PENDING) {
                        (new Product())->update_stock($productId, $newQuantity);
                    }
                }
            }

         
    
         
            if ($order->status == Order::STATUS_CANCELED) {
                foreach ($order->items as $orderItem) {
                    (new Product())->update_stock($orderItem->product_id, -$orderItem->quantity);
                }
            }
    
    

            if ($request->has('amount') && !empty($request->input('amount'))) {
                foreach ($request->input('amount') as $index => $amount) {
                    if (!empty($amount) && !empty($request->input('payment_method_id')[$index])) {
                        $transactionData = [
                            'order_id'          => $order->id,
                            'payment_method_id' => $request->input('payment_method_id')[$index],
                            'amount'            => $amount,
                            'sender_account'    => $request->input('sender_account')[$index] ?? null,
                            'trx_id'            => $request->input('trx_id')[$index] ?? null,
                            'payment_status'    => $request->input('payment_status')[$index] ?? Order::PAYMENT_STATUS_SUCCEESS,
                        ];
            
                        $transactionRequest = new Request($transactionData);
                        (new Transaction())->store_transaction($transactionRequest, $order);
                    }
                }
            }

            $changed = $order->getChanges();
            self::activityLog($request, $original,  $changed, $order);
    
    
            DB::commit();
            success_alert('Order Updated Successfully');
            return redirect()->route('order.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('order.index');
        }
    }
    




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        try {
            (new Order())->delete_order_backend($order);
            DB::commit();
            success_alert('Order Deleted Successfully');
            return redirect()->route('order.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('order.index');
        }
    }



    public function getOrdersInvoiceForDownloadPdf(Request $request)
    {
        $orderIds = $request->input('order_ids', []);
        $orders   = Order::with('shop', 'customer', 'transaction', 'items')
            ->when(
                !empty($orderIds),
                function ($query) use ($orderIds) {
                    $query->whereIn('id', $orderIds);
                }
            )
            ->get();

        $orderData = $orders->map(function ($order) {
            return [
                'name'             => $order?->customer?->name,
                'phone'            => $order->customer_phone,
                'address'          => $order?->customer?->address,
                'payment_type'     => $order?->transaction?->payment_type === 1 ? 'Cash' : 'Online Payment',
                'shop_name'        => $order?->shop?->name,
                'order_date'       => $order->order_date,
                'invoice_number'   => $order->invoice_number,
                'total_amount'     => $order->total_amount,
                'discount_amount'  => $order->discount_amount,
            ];
        });

        return response()->json(['orders' => $orderData]);
    }

    public function downloadOrderInvoicePdf(Request $request)
    {

        $orderIds = $request->query('orders', []);
        $orders   = Order::with('shop', 'customer', 'transaction', 'items')
            ->when(
                !empty($orderIds),
                function ($query) use ($orderIds) {
                    $query->whereIn('id', explode(',', $orderIds));
                }
            )
            ->get();
        $pdf = PDF::loadView('admin.modules.order.invoicePdf', compact('orders'));

        return $pdf->download('invoice.pdf');
    }

    public function exportOrderPDF(Request $request)
    {
        $orders = (new Order())->get_orders($request);

        $data = [
            'orders' => $orders,
            'title'  => 'Orders list'
        ];
        // dd($data);
        $pdf = PDF::loadView('admin.modules.order.pdf', $data);
        return $pdf->download('orders.pdf');
    }




    public function exportOrderCSV(Request $request)
    {
        $orders = (new Order())->get_orders($request);
        $csvFileName = 'Order.csv';
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $handle = fopen($csvFileName, 'w+');
        fputcsv(
            $handle,
            [
                'Invoice Number',
                'Shop Name',
                'Order Date',
                'Customer Name',
                'Customer Phone',
                'Customer Address',
                'Ordered Products',
                'Total Payable Amount',
                'Paid Amount',
                'Order Status',
                'Payment Status'
            ]
        );


        foreach ($orders as $order) {

            fputcsv($handle, [
                $order?->invoice_number,
                $order?->shop?->name,
                $order?->order_date,
                $order?->customer?->name,
                $order?->customer_phone,
                $order?->customer?->address,
                $order->items->map(function ($item) {
                    return $item?->product?->name . '(' . $item->quantity . ')';
                })->implode(', '),
                $order?->total_amount,
                $order?->total_paid_amount,
                Order::STATUS_LIST[$order->status],
                $order->total_payable_amount == $order->total_paid_amount ? 'Paid' : ($order->total_paid_amount == 0 ? 'Unpaid' : ($order->total_payable_amount > $order->total_paid_amount ? 'Partial' : 'Overpaid'))
            ]);
        }

        fclose($handle);

        return response()->download($csvFileName, $csvFileName, $headers);
    }
}

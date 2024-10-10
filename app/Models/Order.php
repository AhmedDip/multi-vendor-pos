<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Manager\OrderManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    const STATUS_PENDING = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_CANCELED = 3;

    const STATUS_LIST = [
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_COMPLETED => 'Completed',
        self::STATUS_CANCELED  => 'Canceled',
    ];

    public const ORDER_STATUS_PENDING   = 1;
    public const ORDER_STATUS_COMPLETED  = 2;
    public const ORDER_STATUS_CANCELED   = 3;


    public const ORDER_STATUS_LIST = [
        self::ORDER_STATUS_PENDING    => 'Pending',
        self::ORDER_STATUS_COMPLETED  => 'Completed',
        self::ORDER_STATUS_CANCELED   => 'Canceled',

    ];

    public const PAYMENT_TYPE_CASH           = 1;
    public const PAYMENT_TYPE_ONLINE_PAYMENT = 2;

    public const PAYMENT_TYPE_LIST = [
        self::PAYMENT_TYPE_CASH           => 'Cash',
        self::PAYMENT_TYPE_ONLINE_PAYMENT => 'Online Payment',
    ];

    public const PAYMENT_STATUS_SUCCEESS   = 1;
    public const PAYMENT_STATUS_FAILED    = 2;

    public const PAYMENT_STATUS_LIST = [
        self::PAYMENT_STATUS_SUCCEESS => 'Success',
        self::PAYMENT_STATUS_FAILED   => 'Failed',
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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function get_orders(Request $request)
    {

        if ($request->input('shop_id')) {
            $query = self::query()->with('customer', 'items', 'orderDetails')->where('shop_id', $request->input('shop_id'));
        } else {
            $query = self::query()->with('customer', 'items', 'orderDetails');
        }

        if ($request->input('customer_id')) {
            $query->where('customer_id', $request->input('customer_id'));
        }
        if ($request->input('invoice_number')) {
            $query->where('invoice_number', $request->input('invoice_number'));
        }
        if ($request->input('total_amount')) {
            $query->where('total_amount', $request->input('total_amount'));
        }
        if ($request->input('discount_amount')) {
            $query->where('discount_amount', $request->input('discount_amount'));
        }

        if ($request->input('order_date')) {
            $query->whereDate('order_date', Carbon::parse($request->input('order_date')));
        }

        if ($request->input('shop_id')) {
            $query->where('shop_id', $request->input('shop_id'));
        }

        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }


        if ($request->input('total_payable_amount')) {
            $query->where('total_payable_amount', $request->input('total_payable_amount'));
        }

        if ($request->input('total_paid_amount')) {
            $query->where('total_paid_amount', $request->input('total_paid_amount'));
        }

        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }



    public function store_order($request): Builder | Model
    {
        $orderManager = new OrderManager();
        $data = $this->prepare_data($request, $orderManager);
        $order = self::query()->create($data['order']);

        foreach ($data['items'] as $item) {
            $order->items()->create($item);
        }

        $order->update([
            'total_amount'         => $data['order']['total_amount'],
            'discount_amount'             => $data['order']['discount_amount'],
            'total_payable_amount' => $data['order']['total_amount'] - $data['order']['discount_amount'],
        ]);

        if (($order->status == Order::STATUS_COMPLETED || $order->status == Order::STATUS_PENDING)) {
            foreach ($data['items'] as $item) {
                (new Product())->update_stock($item['product_id'], $item['quantity']);
            }
        }

        if ($request->has('transactions') && is_array($request->input('transactions'))) {
            foreach ($request->input('transactions') as $transactionData) {
                (new Transaction())->store_transaction(new Request($transactionData), $order);
            }
        }
        return $order;
    }



    public function update_order($request, Order $order): Builder | Model
    {
        $orderManager = new OrderManager();
        $data = $this->prepare_data($request, $orderManager, $order);

        $this->updateOrderStock($order, $data['items']);
        $order->update($data['order']);
        $this->updateOrderItems($order, $data['items']);

        $order->update([
            'total_amount'    => $data['order']['total_amount'],
            'discount_amount' => $data['order']['discount_amount'],
        ]);

        if ($order->status == Order::STATUS_CANCELED) {
            foreach ($order->items as $item) {
                (new Product())->update_stock($item->product_id, -$item->quantity);
            }
        }

        if ($request->has('transactions') && is_array($request->input('transactions'))) {

            $existingTransactions = Transaction::query()->where('order_id', $order->id)->get();
            $updatedTransactionIds = [];

            foreach ($request->input('transactions') as $transactionData) {
                if (isset($transactionData['id'])) {
                    $transaction = Transaction::query()
                        ->where('order_id', $order->id)
                        ->where('id', $transactionData['id'])
                        ->first();

                    if ($transaction) {
                        $transaction->update((new Transaction())->prepare_transaction_data(new Request($transactionData), $order));
                        $updatedTransactionIds[] = $transaction->id;
                    }
                }

                if (!isset($transactionData['id']) || !$transaction) {
                    $newTransaction = (new Transaction())->store_transaction(new Request($transactionData), $order);
                    $updatedTransactionIds[] = $newTransaction->id;
                }
            }

            foreach ($existingTransactions as $existingTransaction) {
                if (!in_array($existingTransaction->id, $updatedTransactionIds)) {
                    $existingTransaction->delete();
                }
            }
        }


        return $order;
    }



    private function updateOrderItems($order, $items)
    {
        $existingItems = $order->items->keyBy('product_id');

        foreach ($items as $itemData) {
            $productId = $itemData['product_id'];
            $quantity = $itemData['quantity'];

            if (isset($existingItems[$productId])) {
                $existingItems[$productId]->update($itemData);
                $existingItems->forget($productId);
            } else {
                $order->items()->create($itemData);
            }
        }

        foreach ($existingItems as $item) {
            $item->delete();
        }
    }



    private function updateOrderStock($order, $items)
    {
        $existingItems = $order->items->keyBy('product_id');
        $stockChanges = [];

        foreach ($items as $itemData) {
            $productId = $itemData['product_id'];
            $quantity = $itemData['quantity'];

            if (isset($existingItems[$productId])) {
                $existingItem = $existingItems[$productId];
                $quantityDifference = $quantity - $existingItem->quantity;
            } else {
                $quantityDifference = $quantity;
            }

            if ($quantityDifference !== 0) {
                $stockChanges[$productId] = ($stockChanges[$productId] ?? 0) + $quantityDifference;
            }
        }

        foreach ($stockChanges as $productId => $quantityChange) {
            (new Product())->update_stock($productId, $quantityChange);
        }
    }







    private function prepare_data(Request $request, OrderManager $orderManager, Order $order = null): array
    {
        if ($request->input('phone')) {
            $customer = Customer::query()->where('phone', $request->input('phone'))->where('shop_id', $request->input('shop_id'))->first();
        }

        if (!$customer) {
            $customer = (new Customer())->store_customer($request);
        }

        if (!empty($request->input('items'))) {
            foreach ($request->input('items') as $item) {
                $product = Product::query()->find($item['product_id']);
                if (!$product) {
                    throw new Exception('Product not found');
                }
            }
        }

        $invoiceNumber = Order::generateInvoiceNumber($request->shop_id);

        $data['order'] = [
            'customer_id'          => $request->input('customer_id') ?? $customer->id,
            'customer_phone'       => $request->input('phone') ?? $customer->phone,
            'shop_id'              => $request->input('shop_id') ?? $customer->shop_id,
            'order_date'           => Carbon::parse($request->input('order_date')) ?? ($order ? $order->order_date : Carbon::now()),
            'total_amount'         => 0,
            'discount_amount'      => $request->input('discount') ?? ($order ? $order->discount_amount : 0),
            'status'               => $request->input('status') ?? ($order ? $order->status : self::STATUS_COMPLETED),
            'note'                 => $request->input('note') ?? ($order ? $order->note : null),
            'invoice_number'       => $request->input('invoice_number') ?? ($order ? $order->invoice_number : $invoiceNumber),
            'total_payable_amount' => 0,
        ];

        if (!empty($request->input('items'))) {
            $calculationResult = $orderManager->calculateItems($request->input('items'));
            $data['order']['total_amount']         = $calculationResult['total_amount'];
            $data['order']['total_payable_amount'] = $calculationResult['total_amount'] - $data['order']['discount_amount'];
            $data['items']                         = $calculationResult['items'];
        } else {
            $data['items'] = [];
        }

        return $data;
    }



    public function delete_order(Order $order): bool
    {
        foreach ($order->items as $item) {
            (new Product())->update_stock($item->product_id, -$item->quantity);
        }
        $order->items()->delete();
        $order->transactions()->delete();
        $order->delete();
        return true;
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function store_order_backend($request, Customer $customer)
    {
        $data = $this->prepare_data_backend($request, $customer);
        $order = self::query()->create($data);
        return $order;
    }

    public function update_order_backend($request, Order $order, $customer)
    {
        $data = $this->prepare_data_backend($request, $customer, $order);
        $order->update($data);
        return $order;
    }

    public function prepare_data_backend($request, Customer $customer, Order $order = null): array
    {
        $invoiceNumber = self::generateInvoiceNumber($request->shop_id);
        if ($order) {
            
            $data = [
                'customer_id'          => $customer->id ?? $order->customer_id,
                'customer_phone'       => $request->input('phone') ?? $order->customer_phone,
                'shop_id'              => $request->input('shop_id') ?? $order->shop_id,
                'order_date'           => $request->input('order_date') ?? $order->order_date,
                'total_amount'         => $request->input('total_amount') ?? $order->total_amount,
                'discount_amount'      => $request->input('discount_amount') ?? $order->discount_amount,
                'total_payable_amount' => $request->input('total_amount') - $request->input('discount_amount') ?? $order->total_payable_amount,
                'status'               => $request->input('status') ?? $order->status,
                'note'                 => $request->input('note') ?? $order->note,
                'invoice_number'       => $request->input('invoice_number') ?? $order->invoice_number,
            ];
        } else {
            $data = [
                'customer_id'          => $customer->id,
                'customer_phone'       => $request->input('phone'),
                'shop_id'              => $request->input('shop_id'),
                'order_date'           => $request->input('order_date'),
                'total_amount'         => $request->input('total_amount'),
                'discount_amount'      => $request->input('discount_amount'),
                'total_payable_amount' => $request->input('total_amount') - $request->input('discount_amount'),
                'status'               => $request->input('status'),
                'note'                 => $request->input('note'),
                'invoice_number'       => $invoiceNumber ?? $request->input('invoice_number'),
            ];
        }

        return $data;
    }

    public function delete_order_backend(Order $order): bool
    {
        foreach ($order->items as $item) {
            (new Product())->update_stock($item->product_id, -$item->quantity);
        }
        $order->items()->delete();
        $order->transactions()->delete();
        $order->delete();

        return true;
    }

    public static function generateInvoiceNumber($shopId)
    {
        $lastOrder = self::where('shop_id', $shopId)->orderByDesc('id')->first();
        $shop = Shop::findOrFail($shopId);
        // $shopName = str_replace(' ', '', strtoupper($shop->name));
        $shopName = substr($shop->name, 0, 3);

        $timestamp = date('ymd');

        if ($lastOrder) {
            $lastInvoiceNumber = $lastOrder->invoice_number;
            $lastInvoiceSequence = (int)substr($lastInvoiceNumber, strlen($shopName) + 1, 6);
            $newInvoiceSequence = str_pad($lastInvoiceSequence + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newInvoiceSequence = '0001';
        }

        $invoiceNumber = sprintf('%s-%s-%s', $shopName, $newInvoiceSequence, $timestamp);

        return $invoiceNumber;
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function get_order_by_invoice_no($invoice_no)
    {
        return self::query()->where('invoice_number', $invoice_no)->first();
    }

    public function get_order_pdf_data($invoice_no)
    {
        $order = self::query()->where('invoice_number', $invoice_no)->first();
        $order->load('customer', 'items.product', 'transactions');
        return $order;
    }

    public static function getOrderCounts()
    {
        return [
            'all'       => self::count(),
            'pending'   => self::where('status', self::ORDER_STATUS_PENDING)->count(),
            'completed' => self::where('status', self::ORDER_STATUS_COMPLETED)->count(),
            'canceled'  => self::where('status', self::ORDER_STATUS_CANCELED)->count(),
            'total'     => self::count(),
        ];
    }


    public function total_order($shop_id = null, $start_date = null, $end_date = null)
    {
        $query = self::where('status', self::STATUS_COMPLETED);

        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }

        if ($shop_id !== null) {
            $query->where('shop_id', $shop_id);
        }

        return $query->count();
    }


    public function top_customer($shop_id = null, $start_date = null, $end_date = null)
    {
        $query = self::select('customer_id', DB::raw('COUNT(*) as total_orders'))
            ->where('status', self::STATUS_COMPLETED)
            ->groupBy('customer_id')
            ->orderByRaw('COUNT(*) DESC')
            ->take(5)
            ->with(['customer:id,name,phone']);

        if ($shop_id !== null) {
            $query->where('shop_id', $shop_id);
        }

        // if ($start_date && $end_date) {
        //     $query->whereBetween('order_date', [$start_date, $end_date]);
        // }

        return $query->get();
    }


    public function sales_by_date()
    {
        return self::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total_sales'))
            ->where('status', self::STATUS_COMPLETED)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date', 'desc')
            ->get();
    }

    public function get_dependancy_data(Request $request)
    {
        $paymentStatusList = [];
        foreach (self::PAYMENT_STATUS_LIST as $key => $value) {
            $paymentStatusList[] = ['id' => $key, 'name' => $value];
        }

        $orderStatusList = [];
        foreach (self::STATUS_LIST as $key => $value) {
            $orderStatusList[] = ['id' => $key, 'name' => $value];
        }

        $shop_id = $request->header('shop-id');

        $payment_method = (new PaymentMethod())->get_payment_methods($shop_id);

        $data = [
            'payment_methods'       => PaymentMethodResource::collection($payment_method),
            'payment_status'        => $paymentStatusList,
            'order_status'          => $orderStatusList,
        ];

        return $data;
    }


    public function discounts(): MorphMany
    {
        return $this->morphMany(Discount::class, 'discountable');
    }



    public function total_discount($shop_id = null, $start_date = null, $end_date = null)
    {
        $query = self::selectRaw('SUM(discount_amount) as total_discount');
    
        if ($shop_id !== null) {
            $query->where('shop_id', $shop_id);
        }
    
        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }
    
        $totalDiscount = $query->value('total_discount') ?? 0;
    
        return round($totalDiscount, 2);
    }
    

    




    public function total_net_amount($shop_id = null, $start_date = null, $end_date = null)
    {

        $query = self::selectRaw('SUM(total_amount) as total_sales');

        if ($shop_id !== null) {
            $query->where('shop_id', $shop_id);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }

        $total_sales = $query->value('total_sales') ?? 0;

        return round($total_sales, 2);
    }


    public function total_sold($shop_id = null)
    {
        if ($shop_id == null) {
            return self::join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->sum('order_items.quantity');
        } else {
            return self::join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.shop_id', $shop_id)
                ->sum('order_items.quantity');
        }
    }

    public function total_sales($shop_id = null)
    {
        if ($shop_id == null) {
            return self::sum('total_amount');
        } else {
            return self::where('shop_id', $shop_id)
                ->sum('total_amount');
        }
    }


    public function convertAppointmentToOrder(Appointment $appointment, Request $request)
    {
        $order = self::query()->create([
            'customer_id'     => $appointment->customer_id,
            'customer_phone'  => $appointment->customer_phone,
            'shop_id'         => $appointment->shop_id,
            'order_date'      => $appointment->date,
            'total_amount'    => $appointment->total_amount,
            'discount_amount' => $appointment->discount_amount,
            'status'          => self::STATUS_COMPLETED,
            'note'            => $appointment->note,
            'invoice_number'  => self::generateInvoiceNumber($appointment->shop_id),
        ]);

        $order->items()->createMany($appointment->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
            ];
        })->toArray());


        // Transaction::prepare_transaction_data($order, $request, [
        //     'total_payable_amount' => $appointment->total_payable_amount,
        //     'total_paid_amount'    => $appointment->total_paid_amount,
        //     'total_due_amount'     => $appointment->total_due_amount,
        //     'discount'             => $appointment->discount,
        //     'discount_percentage'  => $appointment->discount_percentage,
        //     'payment_type'         => $appointment->payment_type,
        //     'sender_number'        => $appointment->sender_number,
        //     'trx_id'               => $appointment->trx_id,
        //     'payment_status'       => $appointment->payment_status,
        //     'order_status'         => $appointment->order_status,
        // ]);

        return $order;
    }

    public function payment_method(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function monthly_sales($shop_id = null)
    {
        $query = self::query()->selectRaw('DATE_FORMAT(order_date, "%b, %Y") as month, SUM(total_amount) as total_sales');

        if ($shop_id) {
            $query->where('shop_id', $shop_id);
        }

        return $query->groupBy('month')->get();
    }

    public function sales_data($shop_id = null, $start_date = null, $end_date = null, $duration = null)
    {
        if ($duration === 'today') {
            $query = self::query()->selectRaw('DATE(order_date) as date, HOUR(order_date) as hour, SUM(total_amount) as total_sales');
        } else {
            $query = self::query()->selectRaw('DATE(order_date) as date, SUM(total_amount) as total_sales');
        }

        if ($shop_id !== null) {
            $query->where('shop_id', $shop_id);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }

        if ($duration === 'today') {
            return $query->groupBy('date', 'hour')->get();
        }

        return $query->groupBy('date')->get();
    }






    public function monthly_orders($shop_id = null, $start_date = null, $end_date = null)
    {
        $query = self::query()->selectRaw('DATE_FORMAT(order_date, "%b, %Y") as month, COUNT(*) as total_orders');

        if ($shop_id) {
            $query->where('shop_id', $shop_id);
        }

        // if ($start_date && $end_date) {
        //     $query->whereBetween('order_date', [$start_date, $end_date]);
        // }

        return $query->groupBy('month')->get();
    }



    public function total_sales_percentage_by_category($shop_id = null, $start_date = null, $end_date = null)
    {
        $totalSoldQuery = self::query()
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id');

        if ($shop_id) {
            $totalSoldQuery->where('orders.shop_id', $shop_id);
        }

        if ($start_date && $end_date) {
            $totalSoldQuery->whereBetween('order_date', [$start_date, $end_date]);
        }

        $totalSold = $totalSoldQuery->first()->total_sold;

        $query = self::query()
            ->selectRaw('categories.name as category, 
                         SUM(order_items.quantity) as total_sold, 
                         ROUND((SUM(order_items.quantity) / ?) * 100, 2) as percentage', [$totalSold])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->groupBy('categories.name')
            ->orderBy('total_sold', 'desc');

        if ($shop_id) {
            $query->where('orders.shop_id', $shop_id);
        }

        if ($start_date && $end_date) {
            $query->whereBetween('order_date', [$start_date, $end_date]);
        }

        return $query->get();
    }



    public function previous_orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id')
            ->where('id', '!=', $this->id)
            ->orderBy('order_date', 'desc')
            ->limit(5);
    }


    public function getDiscountPercentageAttribute()
    {
        return $this->total_amount > 0 ? ($this->discount_amount / $this->total_amount) * 100 : 0;
    }

    public function getTotalPaidAmountAttribute()
    {
        return $this->transactions()->where('payment_status', Order::PAYMENT_STATUS_SUCCEESS)->sum('amount');
    }

    public function getTotalDueAmountAttribute()
    {
        return $this->total_payable_amount - $this->total_paid_amount;
    }



    public function processOrderItemsAndStock($request)
    {
        foreach ($request->product_id as $index => $productId) {
            OrderItem::create([
                'order_id'    => $this->id,
                'product_id'  => $productId,
                'quantity'    => $request->quantity[$index],
                'unit_price'  => $request->unit_price[$index],
                'total_price' => $request->total_price[$index],
                'assign_to'   => $request->assign_to[$index] ?? null,
            ]);
        }


        if ($this->status == Order::STATUS_COMPLETED || $this->status == Order::STATUS_PENDING) {
            foreach ($request->product_id as $index => $productId) {
                (new Product())->update_stock($productId, $request->quantity[$index]);
            }
        }
    }

    public function getPaymentStatusAttribute()
    {
        if ($this->total_paid_amount >= $this->total_payable_amount) {
            return 'Paid';
        } elseif ($this->total_paid_amount == 0) {
            return 'Unpaid';
        } elseif ($this->total_payable_amount > $this->total_paid_amount) {
            return 'Partial';
        } else {
            return 'Overpaid';
        }
    }


    public function getTransactionButton()
    {
        if ($this->total_paid_amount >= $this->total_payable_amount) {
            return false;
        } elseif ($this->total_paid_amount == 0) {
            return true;
        } elseif ($this->total_paid_amount < $this->total_payable_amount) {
            return true;
        } else {
            return false;
        }
    }




    public function getOrderSummaryAttribute()
    {
        return [
            'total_amount'          => $this->total_amount ?? 0,
            'discount_amount'       => $this->discount_amount ?? 0,
            'total_payable_amount'  => $this->total_payable_amount ?? 0,
            'total_paid_amount'     => $this->total_paid_amount ?? 0,
            'total_due_amount'      => $this->total_due_amount > 0 ? $this->total_due_amount : 0,
            'total_overpaid_amount' => $this->total_paid_amount > $this->total_payable_amount ? $this->total_paid_amount - $this->total_payable_amount : 0,
        ];
    }

    public function get_sales_report(Request $request)
    {
        $query = self::query()->with('items.product', 'customer')->orderBy('id', 'desc');

        $shop_id = $request->header('shop-id');

        if ($shop_id) {
            $query->where('shop_id', $shop_id);
        }

        if ($request->input('start_date') && $request->input('end_date')) {
            $query->whereBetween('order_date', [Carbon::parse($request->input('start_date'))->startOfDay(), Carbon::parse($request->input('end_date'))->endOfDay()]);
        }

        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function get_total_sold(Request $request)
    {
        $shop_id = $request->header('shop-id');
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : null;
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : null;

        $query = OrderItem::query();

        if ($shop_id) {
            $query->whereHas('order', function ($orderQuery) use ($shop_id) {
                $orderQuery->where('shop_id', $shop_id);
            });
        }

        if ($startDate && $endDate) {
            $query->whereHas('order', function ($orderQuery) use ($startDate, $endDate) {
                $orderQuery->whereBetween('order_date', [$startDate, $endDate]);
            });
        }

        return $query->sum('quantity') ?? 0;
    }

    public function get_profit_loss_report(Request $request)
    {
        $shopId    = $request->input('shop_id');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date', now()->format('Y-m-d'));

        $orderQuery = Order::query()->where('status', Order::STATUS_COMPLETED);

        if ($shopId) {
            $orderQuery->where('shop_id', $shopId);
        }

        if ($startDate && $endDate) {
            $orderQuery->whereBetween('order_date', [$startDate, $endDate]);
        }

        $totalIncome  = $orderQuery->sum('total_payable_amount');
        $expenseQuery = Expense::query();

        if ($shopId) {
            $expenseQuery->where('shop_id', $shopId);
        }

        if ($startDate && $endDate) {
            $expenseQuery->whereBetween('date', [$startDate, $endDate]);
        }

        $totalExpenses = $expenseQuery->sum('amount');
        $profitOrLoss  = $totalIncome - $totalExpenses;

        $data = [
            'total_income'   => $totalIncome,
            'total_expenses' => $totalExpenses,
            'profit_or_loss' => $profitOrLoss,
        ];

        return $data;
    }

    public function get_top_customers(Request $request)
    {
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
                'customer' => $orders->first()->customer->only(['id', 'name', 'phone']),
                'total_amount' => $orders->sum('total_payable_amount'),
                'total_orders' => $orders->count(),
            ];
        })->sortByDesc('total_amount')->take(10)->values();

        return $topCustomers;
    }



    public function get_total_payable_amount(Request $request)
    {
        $query = self::query();

        $shop_id = $request->header('shop-id');

        if ($shop_id) {
            $query->where('shop_id', $shop_id);
        }

        if ($request->input('start_date') && $request->input('end_date')) {
            $query->whereBetween('order_date', [Carbon::parse($request->input('start_date'))->startOfDay(), Carbon::parse($request->input('end_date'))->endOfDay()]);
        }

        return $query->sum('total_payable_amount');
    }
}

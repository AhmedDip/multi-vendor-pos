@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>Shop</th>
                                <td>{{ $order?->shop?->name }}</td>
                            </tr>

                            <tr>
                                <th>Customer Name</th>
                                <td>
                                    {{ $order?->customer?->name }}
                                </td>
                            </tr>

                            <tr>
                                <th>Customer Phone</th>
                                <td>
                                    {{ $order?->customer?->phone }}
                                </td>
                            </tr>

                            <tr>
                                <th>Order Date</th>
                                <td>
                                    {{ $order?->order_date }}
                                </td>
                            </tr>

                            <tr>
                                <th>Total Amount</th>
                                <td>
                                    {{ $order?->total_amount }} ৳
                                </td>
                            </tr>

                            <tr>
                                <th>Discount Amount</th>
                                <td>
                                    {{ $order?->discount_amount }} ৳
                                </td>
                            </tr>

                            <tr>
                                <th>Payable Amount</th>
                                <td>
                                    {{ $order?->total_payable_amount }} ৳
                                </td>
                            </tr>


                            <tr>
                                <th>Order Items</th>
                                <td>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->orderDetails as $orderItem)
                                                <tr>
                                                    <td>{{ $orderItem->product->name }}</td>
                                                    <td>{{ $orderItem->quantity }}</td>
                                                    <td>{{ $orderItem->unit_price }}</td>
                                                    <td>{{ $orderItem->total_price }}</td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <th>Order Status</th>
                                <td class="text-primary">
                                    {{ \App\Models\Order::STATUS_LIST[$order->status] }}
                                </td>
                            </tr>

                            <tr>
                                @if ($order->transactions->isEmpty())
                                    <p>{{ __('No transactions found for this order.') }}</p>
                                @else
                                    <th>Transactions</th>
                                    <td>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Payment Method') }}</th>
                                                    <th>{{ __('Sender Number') }}</th>
                                                    <th>{{ __('Transaction ID') }}</th>
                                                    <th>{{ __('Payment Status') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->transactions as $transaction)
                                                    <tr>
                                                        <td>{{ $transaction?->payment_method?->name }}</td>
                                                        <td>
                                                            {{ $transaction->sender_account ?? '--' }}
                                                        </td>
                                                        <td>
                                                            {{ $transaction->trx_id ?? '--' }}
                                                        </td>
                                                        <td>{{ \App\Models\Order::PAYMENT_STATUS_LIST[$transaction->payment_status] }}
                                                        </td>
                                                        <td>{{ $transaction?->amount }} ৳</td>
                                                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('jS F, Y g:iA') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                @endif

                            </tr>

                            <tr>
                                <th>Payment Status</th>
                                <td class="text-primary">
                                    @if ($order->total_payable_amount == $order->total_paid_amount)
                                        Paid
                                    @elseif($order->total_paid_amount == 0)
                                        Unpaid
                                    @elseif ($order->total_payable_amount > $order->total_paid_amount)
                                        Partial
                                    @else
                                        Overpaid
                                    @endif
                                </td>
                            </tr>


                            <tr>
                                <th>Note</th>
                                <td>
                                    {!! $order->note !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$order->activity_logs" />
                </div>
            </div>

        </div>
    </div>
@endsection

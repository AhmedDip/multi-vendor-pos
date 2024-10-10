<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ public_path('admin_assets/css/media_library.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ public_path('theme/css/common-css.css') }}" rel="stylesheet" type="text/css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>{{ $title }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #EEEEEE;
            padding: 8px;
            text-align: left;
        }
        td {
            font-size: 13px;
            padding-bottom: 18px;
        }
        th {
            font-size: 14px;
            background-color: #f2f2f2;
        }
        .name-style{
            font-size: 16px;
            margin-top:10px;
        }
        .title-divider {
            display: block;
            max-width: 80px;
            height: 2px;
            background: rgba(233, 102, 49, 1);
            margin: 15px auto;
        }
        .name-style strong{
            font-size: 14px;
        }
        .name-style p{
            margin-top:6px;
        }
        .price-section{
            margin-bottom:10px;
        }
    </style>
</head>
<body>
    
    <h1 class="text-center" style="color:#555555">{{ $title }}</h1>
    <div class="title-divider"></div>
    
    {{-- <div class="pt-5 "> --}}
        {{-- <div class="card-body"> --}}
           
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    {{-- <th>{{ __('Id') }}</th> --}}
                    <th class="text-center">{{ __('Invoice No') }}</th>
                    <th>{{ __('Shop Name') }}</th>
                    <th>{{ __('Order Date') }}</th>
                    <th>
                        {{ __('Customer') }}
                    </th>
                    <th>
                        {{ __('Ordered Product') }}
                    </th>

                    <th>
                        {{ __('Total Payable Amount') }}
                    </th>

                    <th>
                        {{ __('Paid Amount') }}
                    </th>

                    <th>
                        {{ __('Order Status') }}
                    </th>

                    <th>
                        {{ __('Payment Status') }}
                    </th>
                    <th>{{ __('Date Time') }}
                        <x-tool-tip :title="'C = Created at, U = Updated at'" />
                    </th>
                    
                </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            {{-- <td>{{ $order->id }}</td> --}}
                            <td class="text-center">{{ $order->invoice_number }}</a>
                            </td>

                            <td>{{ $order?->shop?->name }}</td>

                            <td>{{ $order->order_date }}</td>

                            <td style="font-size: 12px;"> 
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i class="fa-solid fa-user"></i>
                                        <div class="ms-2">
                                            <span>{{ $order?->customer?->name }}</span>
                                            <p class="mb-0 text-secondary"><small><strong>Phone:</strong>
                                                    {{ $order?->customer?->phone }}</small></p>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td style="font-size: 11px;">
                                <ol>
                                    @foreach($order->orderDetails as $orderDetail)
                                        <li>
                                            <div class="d-flex align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <span>{{ $orderDetail->product->name }}</span>
                                                        <p class="mb-0 text-secondary"><small><strong>X</strong>
                                                                {{ $orderDetail->quantity }}</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                
                                </ol>
                            </td>

                            <td>  
                                {{ $order->total_payable_amount }} Taka
                            </td>

                            <td>  
                                {{ $order->total_paid_amount }} Taka
                            </td>

                            <td>
                                <span class="badge bg-dark">
                                    {{ \App\Models\Order::STATUS_LIST[$order->status] ?? 'Pending' }}
                                </span>
                            </td>
            
                            <td>
                                <span class="badge bg-dark">
                                    @if ($order->total_payable_amount == $order->total_paid_amount)
                                        {{ __('Paid') }}
                                    @elseif($order->total_paid_amount == 0)
                                        {{ __('Unpaid') }}
                                    @elseif ($order->total_payable_amount > $order->total_paid_amount)
                                        {{ __('Partial') }}
                                    @else
                                        {{ __('Overpaid') }}
                                    @endif
                                </span>
                            </td>

                            <td>
                                <x-date-time :created="$order->created_at" :updated="$order->updated_at" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        {{-- </div> --}}
    {{-- </div> --}}
</body>
</html>

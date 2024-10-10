@extends('admin.layouts.app')
@section('content')
    <div class="card mb-4">
        <div class="card-body">
            @include('admin.modules.report.partials.search')

            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-file-invoice-dollar"></i> Sales Report
                    </h6>
                    <span>
                        From <b class="badge badge-light">{{ date('d-F-Y', strtotime($startDate)) }}</b>
                        To <b class="badge badge-light">{{ date('d-F-Y', strtotime($endDate)) }}</b>
                    </span>
                </div>

                <div class="card-body">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Summary</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="font-weight-bold text-primary text-uppercase mb-1">
                                                        <i class="fas fa-box fa-1x"></i>
                                                        Total Products Sold
                                                    </h6>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $totalProductsSold }}
                                                    </div>
                                                </div>
                                                <i class="fas fa-box fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-success shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="font-weight-bold text-success text-uppercase mb-1">
                                                        <i class="fas fa-money-bill-wave"></i> Total Income
                                                    </h6>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        ৳{{ number_format($totalSalesAmount, 2) }}
                                                    </div>
                                                </div>
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive mb-4">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="thead-dark">
                                <tr>
                                    <th><i class="fas fa-receipt"></i> Invoice Number</th>
                                    <th><i class="fas fa-calendar-alt"></i> Order Date</th>
                                    <th><i class="fas fa-user"></i> Customer Name</th>
                                    <th><i class="fas fa-boxes"></i> Products</th>
                                    <th><i class="fas fa-money-bill-wave"></i> Discount Amount</th>
                                    <th><i class="fas fa-money-bill-wave"></i> Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('order.show', $order->id) }}" class="text-primary">
                                                {{ $order->invoice_number }}
                                            </a>
                                        </td>
                                        <td>{{ date('d-F-Y', strtotime($order->order_date)) }}</td>
                                        <td>{{ optional($order->customer)->name }}</td>
                                        <td>
                                            <ul class="list-unstyled">
                                                @foreach ($order->items as $item)
                                                    <li class="mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-2">
                                                                <i class="fas fa-cube text-secondary"></i>
                                                            </div>
                                                            <div>
                                                                <strong>{{ $item?->product?->name }}</strong>
                                                                <span>- {{ $item->quantity }} x
                                                                    ৳{{ number_format($item->unit_price, 2) }}</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="text-danger">৳ {{ number_format($order->discount_amount, 2) }}
                                            </div>
                                        </td>
                                        <td class="font-weight-bold">
                                            <div class="text-success">৳ {{ number_format($order->total_payable_amount, 2) }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

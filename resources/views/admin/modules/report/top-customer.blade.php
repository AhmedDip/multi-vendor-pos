@extends('admin.layouts.app')

@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="font-weight-bold">{{ $cms_content['module'] }}</h3>
            </div>

            @include('admin.modules.report.partials.top-customer-search')
            
            <div class="card mb-4 mt-3">
                <div class="card-body">
                    <h5 class="card-title">Top Customers Summary</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="font-weight-bold text-success text-uppercase mb-1">
                                                <i class="fas fa-users"></i> Total Customers
                                            </h6>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ count($topCustomers) }}
                                            </div>
                                        </div>
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="font-weight-bold text-primary text-uppercase mb-1">
                                                <i class="fas fa-calendar-alt"></i> Date Range Selected 
                                            </h6>
                                            <div class="h5 mb-0 font-weight-bold text-gray-600">
                                                <div class="small text-gray-500">
                                                    From
                                                    <b class="badge badge-light">{{ date('d-F-Y', strtotime($startDate)) }}</b>
                                                    To
                                                    <b class="badge badge-light">{{ date('d-F-Y', strtotime($endDate)) }}</b>
                                                </div>
                                            </div>
                                        </div>
                                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                    </div>

                                </div>
                            </div>
                        
                    </div>
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-hover table-striped align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-hashtag"></i> SL </th>
                            <th><i class="fas fa-user"></i> Customer</th>
                            <th><i class="fas fa-shopping-cart"></i> Total Orders</th>
                            <th><i class="fas fa-money-bill-wave"></i> Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topCustomers as $customer)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('customer.show', $customer['customer']->id) }}" class="text-primary">
                                        {{ $customer['customer']->name }}
                                    </a>

                                    <div class="small text text-muted">
                                        <a href="tel:{{ $customer['customer']->phone }}" class="text-muted">
                                           {{ $customer['customer']->phone }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $customer['total_orders'] }}</td>
                                <td class="font-weight-bold text-success">৳ {{ number_format($customer['total_amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection

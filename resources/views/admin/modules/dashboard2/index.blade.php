@extends('admin.layouts.app')
@section('content')
    @push('css')
        <style>
            .card {
                border-radius: 15px;
                background: linear-gradient(135deg, #f5f7fa 0%, #f8f8f8 100%);
                transition: transform 0.3s ease-in-out;
            }

            .card:hover {
                transform: scale(1.08);
            }

            .card-title {
                font-weight: bold;
                color: #333;
            }

            .card-text {
                font-size: 2rem;
                color: #000000;
            }

            .card-body {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .card-title {
                font-weight: bold;
                margin-bottom: 0;
            }

            .card-text {
                font-size: 2rem;
                margin: 0;
            }

            .icon i {
                color: #7C3AED;
                margin-right: 15px;
            }

            .card:hover .icon i {
                color: #000000;
            }

            .card-head {
                font-size: 1.5rem;
                font-weight: bold;
                color: #333;
                padding: 0.5rem 0;
                background: #f5f7fa;
            }

            #shop-select,
            #duration-select {
                width: 100%;
                margin-bottom: 1rem;
                margin-right: .25rem;
            }

            .icon-re {
                color: #fc1287;
            }
        </style>
    @endpush

    <div class="body-card dashboard">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-6">
                    <h3 class="justify-content-start">Dashboard</h3>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <div class="input-group justify-content-end">
                        <div class="form-group d-flex">
                            <select id="shop-select" class="form-control" onchange="fetchShopData()">
                                <option value="" selected>Select Shop</option>
                                @foreach ($shops as $shop_id => $shop_name)
                                    <option value="{{ $shop_id }}">{{ $shop_name }}</option>
                                @endforeach
                            </select>

                            <select id="duration-select" class="form-control">
                                <option>Select Duration</option>
                                <option value="today">Today</option>
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-3 mt-2">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon mr-3">
                                <i class="fas fa-shopping-cart fa-3x"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Total Orders</h5>
                                <h3 class="card-text" id="total-orders">{{ $total_order }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mt-2">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon mr-3">
                                <i class="fas fa-boxes fa-3x"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Total Sold</h5>
                                <h3 class="card-text" id="total-sold">{{ $total_sold }}</h3>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3 mt-2">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon mr-3">
                                <i class="fas fa-dollar-sign fa-3x"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Total Sales</h5>
                                <h3 class="card-text" id="total-sales">{{ $total_sales }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

    
                <div class="col-md-3 mt-2">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon mr-3">
                                <i class="fas fa-tags fa-3x"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Total Discount</h5>
                                <h3 class="card-text" id="total-discount">{{ $total_discount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-3 mt-2">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon mr-3">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Total Customers</h5>
                                <h3 class="card-text" id="total-customers">{{ $total_customers }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

           

                <div class="col-md-3 mt-2">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon mr-3">
                                <i class="fas fa-store fa-3x"></i>
                            </div>
                            <div>
                                <h5 class="card-title">Total Shop</h5>
                                <h3 class="card-text" id="total-shop">{{ $total_shop }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-2 mt-2">
                    <a href="{{ route('shop.index') }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-re">
                                    <i class="fas fa-store fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="card-title text-center">Shop</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-2 mt-2">
                    <a href="{{ route('product.index') }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-re">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">Product</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-2 mt-2">
                    <a href="{{ route('category.index') }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-re">
                                    <i class="fas fa-list-alt fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">Category</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-2 mt-2">
                    <a href="{{ route('order.index') }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-re">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="card-title text-center">Orders</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-2 mt-2">
                    <a href="{{ route('customer.index') }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-re">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="card-title text-center">Customer</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-md-2 mt-2">
                    <a href="{{ route('sales-report') }}">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <div class="icon-re">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                                <div>
                                    <h5 class="card-title text-center">Report</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid mt-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="shadow-sm p-3 mb-4 bg-white rounded" style="height: 100%;">
                        <h5 class="card-head text-center mb-4">Sales Performance</h5>
                        <div id="sales-data-chart"></div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="shadow-sm p-3 mb-4 bg-white rounded" style="height: 100%;">
                        <h5 class="card-head text-center mb-4">Monthly Sales</h5>
                        <div id="monthly-sales-chart"></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="shadow-sm p-3 mb-4 bg-white rounded" style="height: 100%;">
                        <h5 class="card-head text-center mb-4">Sales by Category</h5>
                        <div id="sales-by-category-chart"></div>
                    </div>
                </div>

                <div class="col-md-6 mt-4">
                    <div class="shadow-sm p-3 mb-4 bg-white rounded" style="height: 100%;">
                        <h5 class="card-head text-center mb-4">Top Customers</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Customer Name</th>
                                        <th>Phone Number</th>
                                        <th>Total Orders</th>
                                    </tr>
                                </thead>
                                <tbody id="top-customers">
                                    @foreach ($top_customer as $index => $customer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $customer['customer']['name'] }}</td>
                                            <td>{{ $customer['customer']['phone'] }}</td>
                                            <td>{{ $customer['total_orders'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mt-4">
                    <div class="shadow-sm p-3 mb-4 bg-white rounded" style="height: 100%;">
                        <h5 class="card-head text-center mb-4">Top Selling Products</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Product Name</th>
                                        <th>Orders</th>
                                    </tr>
                                </thead>

                                <tbody id="top-products">
                                    @foreach ($top_selling_products as $top_selling_product)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="display: flex; align-items: center;">
                                                <div>
                                                    <span
                                                        style="font-weight: bold; display: block;">{{ $top_selling_product->product->name }}</span>
                                                    <p class="mb-0 text-secondary"
                                                        style="margin: 0; font-size: 0.875rem;">
                                                        <small><strong>Slug:</strong>
                                                            {{ $top_selling_product->product->slug }}</small>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>{{ $top_selling_product->total_sold }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
   @include('admin.modules.dashboard2.scripts.dashboard_js_script')
@endpush

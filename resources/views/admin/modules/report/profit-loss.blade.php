@extends('admin.layouts.app')
@section('content')
    <div class="card mb-4">
        <div class="card-body">
            @include('admin.modules.report.partials.profit-loss-search')

            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line"></i> Sales Report
                    </h6>
                    <span>
                        From <b class="badge badge-light">{{ date('d-F-Y', strtotime($startDate)) }}</b>
                        To <b class="badge badge-light">{{ date('d-F-Y', strtotime($endDate)) }}</b>
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-0">Summary</h5>
                                        <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                                    </div>
                                    <hr>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="card border-left-success shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6
                                                                    class="font-weight-bold text-success text-uppercase mb-1">
                                                                    <i class="fas fa-money-bill-wave"></i> Total Income
                                                                </h6>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    ৳ {{ number_format($totalIncome, 2) }}
                                                                </div>
                                                            </div>
                                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card border-left-danger shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6
                                                                    class="font-weight-bold text-danger text-uppercase mb-1">
                                                                    <i class="fas fa-coins"></i> Total Expenses
                                                                </h6>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    ৳ {{ number_format($totalExpenses, 2) }}
                                                                </div>
                                                            </div>
                                                            <i class="fas fa-money-check-alt fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="card border-left-primary shadow h-100 py-2">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6
                                                                    class="font-weight-bold text-primary text-uppercase mb-1">
                                                                    <i class="fas fa-balance-scale"></i> Profit / Loss
                                                                </h6>
                                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                                    ৳ {{ number_format($profitOrLoss, 2) }}
                                                                </div>
                                                            </div>
                                                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

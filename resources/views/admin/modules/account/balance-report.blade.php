@extends('admin.layouts.app')

@section('content')
@push('css')
    <style>
          .custom-card {
                border-radius: 15px;
                background: linear-gradient(135deg, #f5f7fa 0%, #f8f8f8 100%);
                transition: transform 0.3s ease-in-out;
            }

            .custom-card:hover {
                transform: scale(1.08);
            }

    </style>
@endpush
    <div class="card body-card">
        <div class="card-body">
            <h5 class="card-title">Balance Report</h5>
            <form action="{{ route('balance_report_data') }}" method="GET" class="mb-3">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label for="shop_id" class="form-label"><i class="fas fa-store"></i> Shop</label>
                        {{ html()->select('shop_id', $shops, $search['shop_id'] ?? null)->class('form-select form-select-sm')->placeholder('Select Shop') }}
                    </div>

                    <div class="col-md-2">
                        <label for="filter" class="form-label"><i class="fas fa-filter"></i> Filter</label>
                        <select name="filter" id="filter" class="form-select form-select-sm">
                            <option value="daily" {{ request('filter', 'daily') == 'daily' ? 'selected' : '' }}>Daily
                            </option>
                            <option value="weekly" {{ request('filter') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                            <option value="monthly" {{ request('filter') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="date_range" {{ request('filter') == 'date_range' ? 'selected' : '' }}>Date Range
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2" id="start_date_field" style="display: none;">
                        <label for="start_date" class="form-label"><i class="fas fa-calendar-alt"></i> Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control form-control-sm"
                            value="{{ request('start_date') }}">
                    </div>

                    <div class="col-md-2" id="end_date_field" style="display: none;">
                        <label for="end_date" class="form-label"><i class="fas fa-calendar-alt"></i> End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control form-control-sm"
                            value="{{ request('end_date') }}">
                    </div>

                    <div class="col-md-4 mt-4">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i> Apply
                            Filters</button>

                        <a href="{{ route('balance_report_data') }}" class="btn btn-sm btn-danger"><i
                                class="fas fa-times"></i> Reset Filters</a>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-3 custom-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-money-bill-wave"></i> Opening Balance</h5>
                            <p class="card-text text-primary">{{ $data['opening_balance'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3 custom-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-coins"></i> Total Income</h5>
                            <p class="card-text">{{ $data['total_income'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3 custom-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-shopping-cart"></i> Total Expenses</h5>
                            <p class="card-text">{{ $data['total_expenses'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3 custom-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-tags"></i> Total Discounts</h5>
                            <p class="card-text">{{ $data['total_discounts'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3 custom-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-wallet"></i> Closing Balance</h5>
                            <p class="card-text">{{ $data['closing_balance'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterSelect = document.getElementById('filter');
            const startDateField = document.getElementById('start_date_field');
            const endDateField = document.getElementById('end_date_field');

            const toggleDateRangeFields = () => {
                if (filterSelect.value === 'date_range') {
                    startDateField.style.display = 'block';
                    endDateField.style.display = 'block';
                } else {
                    startDateField.style.display = 'none';
                    endDateField.style.display = 'none';
                }
            };

            toggleDateRangeFields();

            filterSelect.addEventListener('change', toggleDateRangeFields);
        });
    </script>
@endpush

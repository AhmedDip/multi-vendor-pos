@extends('admin.layouts.app')

@section('content')
<div class="container pt-5">
    <div class="mb-4 col-md-6">
        <div class="custom-form-group">
            <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
            {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
            <x-validation-error :errors="$errors->first('shop_id')" />
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Order')</h5>
                    <p id="total_order" class="card-text">{{ $order }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Customer')</h5>
                    <p id="total_customer" class="card-text">{{ $customer }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Product')</h5>
                    <p id="total_product" class="card-text">{{ $product }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 row">
        {{-- <div class="col-md-4">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Discount')</h5>
                    <p id="total_discount" class="card-text">{{ $discount }}</p>
                </div>
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Brand')</h5>
                    <p id="total_brand" class="card-text">{{ $brand }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Expense')</h5>
                    <p id="total_expense" class="card-text">{{ $expense }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 row">
        <div class="col-md-4 offset-md-4" id="totalShopCard" style="display: none;">
            <div class="card">
                <div class="text-center card-body">
                    <h5 class="card-title">@lang('Total Shop')</h5>
                    <p class="card-text">{{ $shop }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function toggleTotalShopCard() {
            if ($('#shop_id').val() === '') {
                $('#totalShopCard').show();
            } else {
                $('#totalShopCard').hide();
            }
        }

        toggleTotalShopCard();

        $('#shop_id').change(function() {
            toggleTotalShopCard();
            updateDashboardCards($('#shop_id').val());
        });

        function updateDashboardCards(shopId) {
            $.ajax({
                url: '{{ route("admin.dashboard.update") }}', 
                type: 'GET',
                data: {
                    shop_id: shopId
                },
                success: function(data) {
                    // console.log("Data received: ", data);
                    $('#total_order').text(data.order);
                    $('#total_customer').text(data.customer);
                    $('#total_product').text(data.product);
                    // $('#total_discount').text(data.discount);
                    $('#total_brand').text(data.brand);
                    $('#total_expense').text(data.expense);
                },
                error: function() {
                    console.log('Error retrieving data');
                }
            });
        }
    });
</script>
@endpush
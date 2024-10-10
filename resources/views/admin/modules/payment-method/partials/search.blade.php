{{ html()->form('GET', route('payment-method.index'))->id('search_form')->open() }}
{{ html()->hidden('per_page', $search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-3">
        <label for="shop_id">{{ __('Shop') }}</label>
        {{html()->select('shop_id', $shops, $search['shop_id'] ?? null)->class('form-select form-select-sm')->placeholder('Select Shop')}}
    </div>

    <div class="col-md-3">
        <label for="name">{{ __('Enter Name') }}</label>
        {{html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(trans(''))}}
    </div>
    <div class="col-md-3">
        <label for="status">{{ __('Select Status') }}</label>
        {{html()->select('status', \App\Models\PaymentMethod::STATUS_LIST, $search['status'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Select Status'))}}
    </div>
    
    <div class="col-md-3">
       <label for="order_by_column">{{__('Order By')}}</label>
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}
    </div>
    <div class="col-md-3 mt-3">
        <label for="order_by">{{__('ASC/DESC')}}</label>
        {{ html()->select('order_by',['asc' => trans('ASC'), 'desc' => trans('DESC')], $search['order_by'] ?? null)->placeholder(trans('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
   
    <div class="col-md-4 mt-2">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn btn-sm btn-warning" type="reset">
                        <i class="fa-solid fa-rotate"></i> {{ __('Reset ') }}
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> {{ __('Find') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

{{ html()->form()->close() }}

@push('scripts')
<script>
    $(document).ready(function () {
        $('#reset_search_form').on('click', function () {
            $('#search_form').trigger('reset');
        });
    });
</script>
@endpush


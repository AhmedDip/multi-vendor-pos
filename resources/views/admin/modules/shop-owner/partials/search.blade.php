{{ html()->form('GET', route('shop-owner.index'))->id('search_form')->open() }}
{{ html()->hidden('per_page', $search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="mb-4 row justify-content-center align-items-end">
    <div class="col-md-3">
        <label for="shop_id">{{ __('Shop') }}</label>
        {{html()->select('shop_id', $shops, $search['shop_id'] ?? null)->class('form-select form-select-sm')->placeholder('Select Shop')}}
    </div>
    
    <div class="col-md-3">
        <label for="name">{{ __('Enter  Name') }}</label>
        {{html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. Shop Owner 1'))}}
    </div>
    <div class="col-md-3">
        <label for="email">{{ __('Enter Email') }}</label>
        {{html()->text('email', $search['email'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. shop.owner@gmail.com'))}}
    </div>

    <div class="col-md-3">
        <label for="phone">{{ __('Enter Phone') }}</label>
        {{html()->text('phone', $search['phone'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. 01700000000'))}}
    </div>

    <div class="col-md-3">
        <label for="status">{{ __('Select Status') }}</label>
        {{html()->select('status', \App\Models\User::STATUS_LIST, $search['status'] ?? null)->class('form-control form-control-sm')->placeholder(__('Select Status'))}}
    </div>
    
    <div class="col-md-3">
        {{html()->label( __('Order By Column'), 'order_by')}}
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}
    </div>
  
    
    <div class="col-md-3">
        {{html()->label( __('ASC/DESC'), 'order_by')}}
        {{html()->select('order_by',['asc' => __('ASC'), 'desc' => __('DESC')], $search['order_by'] ?? null)->placeholder(__('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
   
    <div class="mt-2 col-md-4">
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


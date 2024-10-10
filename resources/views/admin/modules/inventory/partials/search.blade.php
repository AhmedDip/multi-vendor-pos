{{ html()->form('GET', route('inventory'))->id('search_form')->open() }}
{{ html()->hidden('per_page', $search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="mb-4 row justify-content-center align-items-end">

    <div class="col-md-3">
        <label for="name">{{ __('Enter Product Name') }}</label>
        {{html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. Product Name'))}}
    </div>
    <div class="col-md-3">
        <label for="sku">{{ __('Enter Product Sku') }}</label>
        {{html()->text('sku', $search['sku'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. sku-1'))}}
    </div>
    <div class="col-md-3">
        <label for="price">{{ __('Enter Price') }}</label>
        {{html()->number('price', $search['price'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. 2000'))}}
    </div>
    <div class="col-md-3">
        <label for="status">{{ __('Select Status') }}</label>
        {{html()->select('status', \App\Models\Product::STATUS_LIST, $search['status'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Select Status'))}}
    </div>
    
    <div class="col-md-3">
       <label for="order_by_column">{{__('Order By')}}</label>
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}
    </div>
    <div class="mt-3 col-md-3">
        <label for="order_by">{{__('ASC/DESC')}}</label>
        {{ html()->select('order_by',['asc' => trans('ASC'), 'desc' => trans('DESC')], $search['order_by'] ?? null)->placeholder(trans('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
    
    
   
    <div class="mt-2 col-md-8">
        <div class="row">
            <div class="col-md-3">
                <div class="d-grid">
                    <button id="reset_fields" class="btn btn-sm btn-warning" type="reset">
                        <i class="fa-solid fa-rotate"></i> {{ __('Reset ') }}
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> {{ __('Find') }}
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <a href="{{ route('inventory.export', $search) }}" class="btn btn-primary btn-sm" type="submit">
                        <i class="fa-solid fa-download"></i> @lang('Export CSV')
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <a href="{{ route('inventory.export-pdf', $search) }}" class="btn btn-danger btn-sm" type="submit">
                        <i class="fa-solid fa-download"></i> @lang('Export PDF')
                    </a>
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


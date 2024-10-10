{{html()->form('get',route('category.index'))->id('search_form')->open()}}
{{html()->hidden('per_page')->value($search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="mb-4 row justify-content-center align-items-end">
    <div class="mb-4 col-md-3">
        {{html()->label(__('Enter category name'), 'name')}}
        {{html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(__('Ex. Dashboard'))}}
    </div>

    <div class="mb-4 col-md-3">
        {{html()->label(__('Select shop'), 'shop_id')}}
        {{html()->select('shop_id', $shops, $search['shop_id'] ?? null)->class('form-select form-select-sm')->placeholder(__('Select shop'))}}
    </div>

    <div class="mb-4 col-md-3">
        {{html()->label( __('Order by'), 'sort_order') }}
        {{html()->select('sort_order',$columns, $search['sort_order'] ?? null)->class('form-select form-select-sm')->placeholder(__('Select order by')) }}
    </div>
    <div class="mb-4 col-md-3">
        {{html()->label( __('ASC/DESC'), 'order_by')}}
        {{html()->select('order_by',['asc' => __('ASC'), 'desc' => __('DESC')], $search['order_by'] ?? null)->placeholder(__('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
    <div class="mb-4 col-md-8">
        <div class="row">
            <div class="col-md-3">
                <div class="d-grid">
                    <button id="reset_fields" class="btn btn-warning btn-sm" type="reset">
                        <i class="fa-solid fa-rotate"></i> @lang('Reset')
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> @lang('Search')
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <a href="{{ route('category.export', $search) }}" class="btn btn-primary btn-sm" type="submit">
                        <i class="fa-solid fa-download"></i> @lang('Export CSV')
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-grid">
                    <a href="{{ route('category.export-pdf', $search) }}" class="btn btn-danger btn-sm" type="submit">
                        <i class="fa-solid fa-download"></i> @lang('Export PDF')
                    </a>
                </div>
            </div>
            
        </div>
    </div>
</div>
{{html()->form()->close()}}




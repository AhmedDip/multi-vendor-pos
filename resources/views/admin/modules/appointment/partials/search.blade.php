{{html()->form('get',route('appointment.index'))->id('search_form')->open()}}
{{html()->hidden('per_page')->value($search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="mb-4 row justify-content-center align-items-end">
    <div class="mb-4 col-md-4">
        {{html()->label(__('Customer Name'), 'name')}}
        {{html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(__('Ex. john'))}}
    </div>
    <div class="mb-4 col-md-4">
        {{html()->label(__('Phone'), 'phone')}}
        {{html()->text('phone', $search['phone'] ?? null)->class('form-control form-control-sm')->placeholder(__('Ex. 017xxxxxxxx'))}}
    </div>
    <div class="mb-4 col-md-4">
        {{ html()->label('Shop', 'shop_id') }}
        {{ html()->select('shop_id', $shop)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder(__('Select Shop')) }}
    </div>
    <div class="mb-4 col-md-4">
        {{ html()->label('Category', 'category_id') }}
        {{ html()->select('category_id', $category)->class('form-select form-select-sm ' . ($errors->has('category_id') ? 'is-invalid' : ''))->placeholder(__('Select Category')) }}
    </div>
    <div class="mb-4 col-md-4">
        {{html()->label(__('Appointment Date'), 'date')}}
        {{ html()->date('date', $search['date'] ?? null)->class('form-control form-control-sm')}}
    </div>
    
    <div class="mb-4 col-md-4">
        {{html()->label( __('Order By Column'), 'order_by')}}
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}
    </div>
  
    
    <div class="mb-4 col-md-4">
        {{html()->label( __('ASC/DESC'), 'order_by')}}
        {{html()->select('order_by',['asc' => __('ASC'), 'desc' => __('DESC')], $search['order_by'] ?? null)->placeholder(__('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
    <div class="mb-4 col-md-4">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn btn-warning btn-sm" type="reset">
                        <i class="fa-solid fa-rotate"></i> @lang('Reset')
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> @lang('Search')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{html()->form()->close()}}


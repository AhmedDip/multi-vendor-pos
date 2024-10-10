{{html()->form('get',route('category.index'))->id('search_form')->open()}}
{{html()->hidden('per_page')->value($search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-4 mb-4">
        {{html()->label(__('Enter Plan name'), 'plan')}}
        {{html()->text('plan', $search['plan'] ?? null)->class('form-control form-control-sm')->placeholder(__('Ex. Dashboard'))}}
    </div>
    <div class="col-md-4 mb-4">
        {{html()->label( __('ASC/DESC'), 'order_by')}}
        {{html()->select('order_by',['asc' => __('ASC'), 'desc' => __('DESC')], $search['order_by'] ?? null)->placeholder(__('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
    <div class="col-md-4 mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn  btn-warning btn-sm" type="reset">
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


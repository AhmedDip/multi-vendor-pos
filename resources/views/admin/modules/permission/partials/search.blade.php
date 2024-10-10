<form method="get" id="search_form">
<input type="hidden" name="per_page" value="{{ $search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION }}">
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-3">
        <label for="name">{{ __('Enter Permission Name') }}</label>
        {{-- <input type="text" name="name" value="{{ $search['name'] ?? null }}" class="form-control form-control-sm" placeholder="Ex. category.update"> --}}
        {{ html()->text('name', $search['name'] ?? null)->class('form-control form-control form-control-sm')->placeholder(trans('Ex. category.update')) }}
    </div>
    <div class="col-md-3">
        <label for="order_by_column">{{__('Order By')}}</label>
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}

    </div>
    <div class="col-md-3">
        <label for="order_by">{{__('ASC/DESC')}}</label>
        {{ html()->select('order_by',['asc' => trans('ASC'), 'desc' => trans('DESC')], $search['order_by'] ?? null)->placeholder(trans('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn btn-sm btn-warning" type="reset">
                        <i class="fa-solid fa-rotate"></i> {{ __('Reset') }}
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-sm btn-success" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> {{ __('Find') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</form>
{{-- {!! Form::close() !!} --}}


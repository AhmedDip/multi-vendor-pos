{{ html()->form('GET', route('role-assign.index'))->id('search_form')->open() }}
{{ html()->hidden('per_page', $search['per_page'] ?? 10) }}
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-3 mb-3">
        <label for="name">{{ __('Enter User Name') }}</label>
        {{ html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. Jacqueline Fernandez')) }}
    </div>
    <div class="col-md-3 mb-3">
        <label for="email">{{__('Enter User Email')}}</label>
        {{ html()->text('email', $search['email'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. jacquline@gmail.com')) }}
    </div>
    <div class="col-md-3 mb-3">
        <label for="phone">{{__('Enter User Phone')}}</label>
        {{ html()->text('phone', $search['phone'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. 01711980213')) }}
    </div>
    <div class="col-md-3 mb-3">
        <label for="status">{{__('User Status')}}</label>
        {{ html()->select('status',\App\Models\User::STATUS_LIST, $search['status'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Select User Status')) }}
    </div>
    <div class="col-md-3 mb-3">
        <label for="role">{{__('User Role')}}</label>
        {{ html()->select('role',$roles, $search['role'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Select User Role')) }}
    </div>
    <div class="col-md-3 mb-3">
        <label for="order_by_column">{{__('Order By')}}</label>
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}
    </div>
    <div class="col-md-3 mb-3">
        <label for="order_by">{{__('ASC/DESC')}}</label>
        {{ html()->select('order_by',['asc' => trans('ASC'), 'desc' => trans('DESC')], $search['order_by'] ?? null)->placeholder(trans('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
    <div class="col-md-3 mb-3">
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
{{ html()->form()->close() }}


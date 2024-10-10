{{ html()->form('GET', route('role.index'))->id('search_form')->open() }}
{{ html()->hidden('per_page', $search['per_page'] ?? \App\Manager\Constants\GlobalConstant::DEFAULT_PAGINATION)}}
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-3">
        {{-- {{html()->label('Enter menu name', 'name')}} --}}
        <label for="name">{{ __('Enter Role Name') }}</label>
        {{html()->text('name', $search['name'] ?? null)->class('form-control form-control-sm')->placeholder(trans('Ex. Admin'))}}
    </div>
    <div class="col-md-3">
        {{-- {!! Form::label('order_by_column', 'Order by') !!}
        {!! Form::select('order_by_column',$columns, $search['order_by_column'] ?? null, ['class' => 'form-select', 'placeholder' => 'Select Order By']) !!} --}}
       <label for="order_by_column">{{__('Order By')}}</label>
        {{ html()->select('order_by_column',$columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(trans('Sort Order By')) }}
    </div>
    <div class="col-md-3">
        {{-- {!! Form::label('order_by', 'ASC/DESC') !!}
        {!! Form::select('order_by',['asc' => 'ASC', 'desc' => 'DESC'], $search['order_by'] ?? null, ['class' => 'form-select', 'placeholder' => 'Select ASC/DESC']) !!} --}}
        <label for="order_by">{{__('ASC/DESC')}}</label>
        {{ html()->select('order_by',['asc' => trans('ASC'), 'desc' => trans('DESC')], $search['order_by'] ?? null)->placeholder(trans('Select ASC/DESC'))->class('form-select form-select-sm') }}
    </div>
   
    <div class="col-md-4 mt-2">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_search_form" class="btn  btn-warning btn-sm" type="reset">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> Search
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


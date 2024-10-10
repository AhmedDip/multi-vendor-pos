{{ html()->form('get', route('blog-category.index'))->open(['id' => 'search_form']) }}
{{ html()->hidden('per_page', $search['per_page'] ?? 10) }}
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-3">
        {{ html()->label(__('Enter Category name'))->for('name') }}
        {{ html()->text('name')->class('form-control form-control-sm ')->placeholder(__('Enter Category name')) }}
    </div>
    <div class="col-md-3">
        {{ html()->label(__('Status'))->for('status') }}
        {{ html()->select('status', $status, null)->class('form-select form-select-sm ')->placeholder(__('Select Status')) }}
    </div>
    <div class="col-md-3">
        {{ html()->label(__('Order By'))->for('order_by_column') }}
        {{ html()->select('order_by_column', $columns, $search['order_by_column'] ?? null)->class('form-select form-select-sm')->placeholder(__('Select Order By')) }}
    </div>
    <div class="col-md-3">

        {{ html()->label(__('ASC/DESC'))->for('order_by') }}
        {{ html()->select('order_by', ['asc' => 'ASC', 'desc' => 'DESC'], $search['order_by'] ?? null)->class('form-select form-select-sm')->placeholder(__('Select ASC/DESC')) }}
    </div>
    <div class="col-md-3 mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn  btn-warning" type="reset">
                        <i class="fa-solid fa-rotate"></i> {{ __('Reset ') }}
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-success" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> {{ __('Find') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{{ html()->form()->close() }}
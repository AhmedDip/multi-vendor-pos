{!! Form::open(['route' => 'admin.index', 'method' => 'get', 'id'=>'search_form']) !!}
{!! Form::hidden('per_page', $search['per_page'] ?? 10) !!}
<div class="row justify-content-center mb-4 align-items-end">
    <div class="col-md-3 mb-4">
        {!! Form::label('name', 'Enter name') !!}
        {!! Form::text('name', $search['name'] ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Enter name']) !!}
    </div>
    <div class="col-md-3 mb-4">
        {!! Form::label('phone', 'Phone') !!}
        {!! Form::text('phone', $search['phone'] ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Enter Phone']) !!}
    </div>
    <div class="col-md-3 mb-4">
        {!! Form::label('email', 'Email') !!}
        {!! Form::text('email', $search['email'] ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Enter Email']) !!}
    </div>
    <div class="col-md-3 mb-4">
        {!! Form::label('status', 'Status') !!}
        {!! Form::select('status',$status, $search['status'] ?? null, ['class' => 'form-select', 'placeholder' => 'Select Status']) !!}
    </div>
    <div class="col-md-3 mb-4">
        {!! Form::label('role', 'Role') !!}
        {!! Form::select('role',$roles, $search['role'] ?? null, ['class' => 'form-select', 'placeholder' => 'Select Role']) !!}
    </div>
    <div class="col-md-3 mb-4">
        {!! Form::label('order_by_column', 'Order by') !!}
        {!! Form::select('order_by_column',$columns, $search['order_by_column'] ?? null, ['class' => 'form-select', 'placeholder' => 'Select Order By']) !!}
    </div>
    <div class="col-md-3 mb-4">
        {!! Form::label('order_by', 'ASC/DESC') !!}
        {!! Form::select('order_by',['asc' => 'ASC', 'desc' => 'DESC'], $search['order_by'] ?? null, ['class' => 'form-select', 'placeholder' => 'Select ASC/DESC']) !!}
    </div>
    <div class="col-md-3 mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn  btn-warning" type="reset">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-success" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> Search
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}


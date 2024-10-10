@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {!! Form::model($address, ['route' => ['address.update', $address->id, http_build_query(request()->query())], 'method' => 'put', 'id' => 'create_form']) !!}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.admin-profile.address.partials.form')
            </div>
            <div class="row justify-content-center">
                <div class="col-md-2">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

@endsection

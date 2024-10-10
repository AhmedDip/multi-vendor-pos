@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{html()->form('post',route('shipping-method.store'))->id('create_form')->open()}}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.shipping-method.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'create'"/>
                </div>
            </div>
            {{html()->form()->close()}}
        </div>
    </div>

@endsection

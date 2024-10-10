@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{html()->form('post',route('payment-method.store'))->id('create_form')->open()}}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.payment-method.partials.form')
                <div class="row justify-content-center">
                    <div class="col-2">
                        <x-submit-button :type="'create'"/>
                    </div>
                </div>
            </div>
            {{html()->form()->close()}}
        </div>
    </div>

@endsection

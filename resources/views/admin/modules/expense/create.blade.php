@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            {{html()->form('post',route('expense.store'))->id('create_form')->open()}}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.expense.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'create'"/>
                </div>
            </div>
            {{html()->form()->close()}}
        </div>
    </div>

@endsection

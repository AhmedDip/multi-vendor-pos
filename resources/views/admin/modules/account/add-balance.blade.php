@extends('admin.layouts.app')

@section('content')
<div class="card body-card">
    <div class="card-body">
        {{ html()->form('post', route('add-balance'))->id('create_form')->open() }}
        <div class="row mb-2 justify-content-center">
            <div class="col-md-6">
                @include('admin.modules.account.partials.form')
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-3">
                <x-submit-button :type="'create'" />
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>
@endsection

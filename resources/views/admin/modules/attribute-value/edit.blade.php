@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{html()->modelForm($attribute_value, 'PUT', route('attribute-value.update', $attribute_value->id))->id('create_form')->open()}}

            <div class="row justify-content-center align-items-end">
                @include('admin.modules.attribute-value.partials.form')
                <div class="row justify-content-center">
                    <div class="col-2">
                        <x-submit-button :type="'update'"/>
                    </div>
                </div>
            </div>

            {{html()->form()->close()}}
        </div>
    </div>

@endsection

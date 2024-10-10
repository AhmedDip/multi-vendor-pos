@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            {{html()->modelForm($membershipCardType, 'PUT', route('membership-card-type.update',  $membershipCardType->id))->id('create_form')->open()}}

            <div class="row justify-content-center align-items-end">
                @include('admin.modules.membershipCardType.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>

            {{html()->form()->close()}}
        </div>
    </div>

@endsection

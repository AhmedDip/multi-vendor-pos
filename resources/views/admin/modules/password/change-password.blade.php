@extends('admin.layouts.app')

@section('content')
    <div class="body-card">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>{{__('Change Password')}}</h4>
                    </div>
                    <div class="card-body">
                        {{html()->form('POST', route('update-password'))->open()}}
                        <div class="custom-input-group">
                            <label for="password">{{__('New password')}}</label>
                            {{html()->password('password')->class('form-control '.($errors->has('password') ? 'is-invalid' : null))->placeholder(trans('Enter New Password'))}}
                            <x-validation :error="$errors->first('password')"/>
                        </div>
                        <div class="custom-input-group">
                            <label for="password">{{__('Repeat new password')}}</label>
                            {{html()->password('password_confirmation')->class('form-control')->placeholder(trans('Repeat New Password'))}}
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn theme-button">
                                {{ __('Save changes')}}
                            </button>
                        </div>
                        {{html()->form()->close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        .body-card{
            background: transparent !important;
        }
    </style>
@endpush

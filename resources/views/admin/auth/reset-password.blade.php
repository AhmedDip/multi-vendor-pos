@extends('admin.layouts.auth-layout')
@section('page_title', 'Login')
@section('content')
    <div class="login-card">
        <h1>@lang('Reset password')!</h1>
        <p class="text-muted">@lang('Please enter your new password and repeat it in repeat box')</p>
        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="auth-input-group mt-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="@lang('Enter Email')" value="{{old('email', $request->email)}}">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 error-msg" />
                </div>
                <div class="input-group mt-3">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="@lang('New Password')">
                    <x-input-error :messages="$errors->get('password')" class="mt-2 error-msg" />
                </div>
                <div class="input-group mt-3">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('Repeat New Password')">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 error-msg" />
                </div>
                <div class="mt-5">
                    <button class="btn btn-success login-button">@lang('Submit')</button>
                </div>
            </div>
        </form>
    </div>
@endsection

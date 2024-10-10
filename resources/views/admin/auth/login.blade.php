@extends('admin.layouts.auth-layout')
@section('page_title', 'Login')
@section('content')
<div class="login-card">
    <h1>@lang('Welcome back') !</h1>
    <p class="text-muted">@lang('Welcome back to your account. please log in to your account') </p>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="auth-input-group mt-5">
            <div class="input-group">
                <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                <input type="text" name="email" class="form-control" placeholder="{{__('Email Address')}}">
                <x-input-error :messages="$errors->get('email')" class="mt-2 error-msg" />
            </div>
            <div class="input-group mt-3">
                <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="@lang('Password')">
                <x-input-error :messages="$errors->get('password')" class="mt-2 error-msg" />
            </div>
            <div class="d-flex align-items-center justify-content-between my-3">
                <div class="form-check ms-4">
                    <input class="form-check-input" name="remember" type="checkbox" id="flexCheckDefault">
                    <label class="form-check-label" for="flexCheckDefault">
                        @lang('Remember me')
                    </label>
                </div>
                <div class="forgot-password">
                    <a href="{{route('password.request')}}">@lang('Forgot password')?</a>
                </div>
            </div>
            <div class="mt-5">
                <button class="btn btn-success login-button">@lang('SIGN IN')</button>
            </div>
        </div>
    </form>
</div>
@endsection

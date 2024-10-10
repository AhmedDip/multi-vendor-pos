@extends('admin.layouts.auth-layout')
@section('page_title', 'Login')
@section('content')
    <div class="login-card">
        <h1>@lang('Forgot password')!</h1>
        <p class="text-muted">@lang('If you forget your password you can rest it using phone or email')</p>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="auth-input-group mt-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="@lang('Enter Email/Phone')">
                    <x-input-error :messages="$errors->get('email')" class="mt-2 error-msg" />
                </div>
                <div class="d-flex align-items-center justify-content-end my-3">
                    <div class="forgot-password">
                        <a href="{{route('login')}}">@lang('Remembered password? Login')</a>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-success login-button">@lang('Submit')</button>
                </div>
            </div>
        </form>
    </div>
@endsection

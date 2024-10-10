@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {!! Form::open(['route' => 'profile.update-password', 'method' => 'post', 'id' => 'create_form']) !!}
            <div class="row justify-content-center align-items-end">
                <div class="col-md-6">
                    <div class="custom-form-group">
                        {!! Form::label('password', 'New Password') !!} <x-required/>
                        <div class="input-group">
                            {!! Form::password('password',  ['class' => 'form-control form-control-sm '. ($errors->has('password') ? 'is-invalid' : ''), 'placeholder' => 'Enter new password']) !!}
                            <div class="input-group-text"><i class="fa-solid fa-eye"></i></div>
                        </div>
                        <x-validation-error :errors="$errors->first('password')"/>
                    </div>
                </div>
                <div class="col-md-3 mt-4">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>

@endsection
@push('script')
    <script>
        $('.input-group-text').on('click', function () {
            $(this).children('i').toggleClass('fa-eye-slash');
            let type = $(this).siblings('input').attr('type');
            if (type == 'password') {
                $(this).siblings('input').attr('type', 'text');
            }else{
                $(this).siblings('input').attr('type', 'password');
            }
        })
    </script>
@endpush

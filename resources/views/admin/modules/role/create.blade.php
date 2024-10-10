@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{-- {!! Form::open(['route' => 'role.store', 'method' => 'post', 'id' => 'create_form']) !!} --}}
            {{ html()->form('POST', route('role.store'))->id('create_form')->open() }}
            <div class="row justify-content-center align-items-end mb-4">
                <div class="col-md-4">
                    @include('admin.modules.role.partials.form')
                </div>
                <div class="col-md-2">
                    <x-submit-button :type="'create'"/>
                </div>
            </div>

            {{-- {!! Form::close() !!} --}}
            {{ html()->form()->close() }}
        </div>
    </div>

@endsection

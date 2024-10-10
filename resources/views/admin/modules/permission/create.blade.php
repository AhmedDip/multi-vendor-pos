@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-4">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center">
                    <p>You can re-generate permission here</p>
                    {{-- {!! Form::open(['route' => 'permission.store']) !!} --}}
                    {{ html()->form('POST', route('permission.store'))->open() }}
                    <button class="btn btn-success btn-sm mt-4"><i class="fa-solid fa-repeat"></i> Re-Generate</button>
                    {{-- {!! Form::close() !!} --}}
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
@endsection

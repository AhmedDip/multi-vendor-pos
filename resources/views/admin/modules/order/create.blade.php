@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{ html()->form('post', route('order.store'))->id('create_form')->open() }}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.order.partials.form')
                <div class="col-md-2 mt-3">
                    <x-submit-button :type="'create'" />
                </div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

@push('scripts')
@include('admin.modules.order.scripts.order_store_js')
@endpush



@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{ html()->modelForm($order, 'PUT', route('order.update', $order->id))->id('create_form')->open() }}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.order.partials.form')
                <div class="col-md-2 mt-2">
                    <x-submit-button :type="'edit'" />
                </div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

@push('scripts')
 @include('admin.modules.order.scripts.order_update_js')
@endpush






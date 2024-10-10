@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.payment-method.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($payment_methods as $payment_method)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$payment_methods"/>
                        </td>
                        <td>
                            {{$payment_method->shop?->name}}
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p class="text">{{$payment_method->name}}</p>
                                    <p class="text-info">{{$payment_method->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{$payment_method->sort_order}}</td>
                        <td>
                            <x-date-time :created="$payment_method->created_at" :updated="$payment_method->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('payment-method.show', $payment_method->id)"/>
                                <x-edit-button :route="route('payment-method.edit', $payment_method->id)"/>
                                <x-delete-button :route="route('payment-method.destroy', $payment_method->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$payment_methods"/>
        </div>
    </div>
@endsection

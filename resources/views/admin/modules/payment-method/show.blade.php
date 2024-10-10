@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th>@lang('ID')</th>
                            <td>{{$payment_method->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$payment_method->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$payment_method->slug}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($payment_method->status == \App\Models\PaymentMethod::STATUS_ACTIVE)
                                    <x-active :status="$payment_method->status"/>
                                @else
                                    <x-inactive :status="$payment_method->status" :title="'Inactive'"/>
                                    {{\App\Models\PaymentMethod::STATUS_LIST[$payment_method->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$payment_method?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$payment_method?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$payment_method->created_at"/>
                                <small class="text-success">{{$payment_method->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$payment_method->created_at" :updated="$payment_method->updated_at"/>
                                <small class="text-success">{{$payment_method->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$payment_method->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

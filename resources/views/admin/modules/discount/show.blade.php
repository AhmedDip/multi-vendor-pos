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
                            <td>{{$discount->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$discount->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$discount->slug}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Discount Amount')</th>
                            <td>{{$discount->amount}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Discount Percentage')</th>
                            <td>{{$discount->percentage}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Coupon Code')</th>
                            <td>{{$discount->coupon_code}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($discount->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($discount->status == \App\Models\discount::STATUS_ACTIVE)
                                    <x-active :status="$discount->status"/>
                                @else
                                    <x-inactive :status="$discount->status" :title="'Inactive'"/>
                                    {{\App\Models\discount::STATUS_LIST[$discount->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$discount?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$discount?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$discount->created_at"/>
                                <small class="text-success">{{$discount->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$discount->created_at" :updated="$discount->updated_at"/>
                                <small class="text-success">{{$discount->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$discount->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

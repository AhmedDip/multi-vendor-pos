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
                            <td>{{$warehouse->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$warehouse->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$warehouse->slug}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Phone')</th>
                            <td>{{$warehouse->phone}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Street Address')</th>
                            <td>{{$warehouse->street_address}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($warehouse->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($warehouse->status == \App\Models\Warehouse::STATUS_ACTIVE)
                                    <x-active :status="$warehouse->status"/>
                                @else
                                    <x-inactive :status="$warehouse->status" :title="'Inactive'"/>
                                    {{\App\Models\Warehouse::STATUS_LIST[$warehouse->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$warehouse?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$warehouse?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$warehouse->created_at"/>
                                <small class="text-success">{{$warehouse->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$warehouse->created_at" :updated="$warehouse->updated_at"/>
                                <small class="text-success">{{$warehouse->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$warehouse->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection
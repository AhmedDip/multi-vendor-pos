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
                            <td>{{$package->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Plan')</th>
                            <td>{{$package->plan}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Tagline')</th>
                            <td>{{$package->tagline}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Quota')</th>
                            <td>{{$package->quota}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Price')</th>
                            <td>{{$package->price}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Sort Order')</th>
                            <td>{{$package->sort_order}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($package->status == \App\Models\Package::STATUS_ACTIVE)
                                    <x-active :status="$package->status"/>
                                @else
                                    <x-inactive :status="$package->status" :title="'Inactive'"/>
                                    {{\App\Models\Package::STATUS_LIST[$package->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$package?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$package?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$package->created_at"/>
                                <small class="text-success">{{$package->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$package->created_at" :updated="$package->updated_at"/>
                                <small class="text-success">{{$package->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img src="{{get_image($package?->photo?->photo)}}" alt="image" class="img-thumbnail">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$package->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

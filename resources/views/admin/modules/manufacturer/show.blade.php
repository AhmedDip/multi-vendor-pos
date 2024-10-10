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
                            <td>{{$manufacturer->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$manufacturer->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$manufacturer->slug}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($manufacturer->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($manufacturer->status == \App\Models\Manufacturer::STATUS_ACTIVE)
                                    <x-active :status="$manufacturer->status"/>
                                @else
                                    <x-inactive :status="$manufacturer->status" :title="'Inactive'"/>
                                    {{\App\Models\Manufacturer::STATUS_LIST[$manufacturer->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$manufacturer?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$manufacturer?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$manufacturer->created_at"/>
                                <small class="text-success">{{$manufacturer->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$manufacturer->created_at" :updated="$manufacturer->updated_at"/>
                                <small class="text-success">{{$manufacturer->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img src="{{get_image($manufacturer?->photo?->photo)}}" alt="image" class="img-thumbnail">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$manufacturer->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

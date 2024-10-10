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
                            <td>{{$brand->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$brand->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$brand->slug}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($brand->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($brand->status == \App\Models\Brand::STATUS_ACTIVE)
                                    <x-active :status="$brand->status"/>
                                @else
                                    <x-inactive :status="$brand->status" :title="'Inactive'"/>
                                    {{\App\Models\Brand::STATUS_LIST[$brand->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Description')</th>
                            <td>{{$brand->description}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$brand?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$brand?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$brand->created_at"/>
                                <small class="text-success">{{$brand->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$brand->created_at" :updated="$brand->updated_at"/>
                                <small class="text-success">{{$brand->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img src="{{get_image($brand?->photo?->photo)}}" alt="image" class="img-thumbnail">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$brand->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

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
                            <td>{{$category->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$category->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$category->slug}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($category->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($category->status == \App\Models\Category::STATUS_ACTIVE)
                                    <x-active :status="$category->status"/>
                                @else
                                    <x-inactive :status="$category->status" :title="'Inactive'"/>
                                    {{\App\Models\Category::STATUS_LIST[$category->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Description')</th>
                            <td>{{$category->description}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$category?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$category?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$category->created_at"/>
                                <small class="text-success">{{$category->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$category->created_at" :updated="$category->updated_at"/>
                                <small class="text-success">{{$category->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img src="{{get_image($category?->photo?->photo)}}" alt="image" class="img-thumbnail">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$category->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

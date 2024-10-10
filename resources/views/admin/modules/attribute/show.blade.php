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
                            <td>{{$attribute->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$attribute->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$attribute->slug}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($attribute->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($attribute->status == \App\Models\attribute::STATUS_ACTIVE)
                                    <x-active :status="$attribute->status"/>
                                @else
                                    <x-inactive :status="$attribute->status" :title="'Inactive'"/>
                                    {{\App\Models\attribute::STATUS_LIST[$attribute->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Description')</th>
                            <td>{{$attribute->description}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$attribute?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$attribute?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$attribute->created_at"/>
                                <small class="text-success">{{$attribute->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$attribute->created_at" :updated="$attribute->updated_at"/>
                                <small class="text-success">{{$attribute->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img src="{{get_image($attribute?->photo?->photo)}}" alt="image" class="img-thumbnail">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$attribute->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

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
                            <td>{{$attribute_value->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Attribute')</th>
                            <td>{{$attribute_value->attribute_id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$attribute_value->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$attribute_value->slug}}</td>
                        </tr>
                        <tr>
                            <th>Sort Order</th>
                            <td>{{number($attribute_value->sort_order)}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($attribute_value->status == \App\Models\AttributeValue::STATUS_ACTIVE)
                                    <x-active :status="$attribute_value->status"/>
                                @else
                                    <x-inactive :status="$attribute_value->status" :title="'Inactive'"/>
                                    {{\App\Models\AttributeValue::STATUS_LIST[$attribute_value->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Description')</th>
                            <td>{{$attribute_value->description}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$attribute_value?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$attribute_value?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$attribute_value->created_at"/>
                                <small class="text-success">{{$attribute_value->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$attribute_value->created_at" :updated="$attribute_value->updated_at"/>
                                <small class="text-success">{{$attribute_value->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img src="{{get_image($attribute_value?->photo?->photo)}}" alt="image" class="img-thumbnail">
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$attribute_value->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

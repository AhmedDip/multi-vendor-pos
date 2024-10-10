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
                            <td>{{$feature->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$feature->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Slug')</th>
                            <td>{{$feature->slug}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Sort Order')</th>
                            <td>{{$feature->sort_order}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($feature->status == \App\Models\Feature::STATUS_ACTIVE)
                                    <x-active :status="$feature->status"/>
                                @else
                                    <x-inactive :status="$feature->status" :title="'Inactive'"/>
                                    {{\App\Models\Feature::STATUS_LIST[$feature->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$feature?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$feature?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$feature->created_at"/>
                                <small class="text-success">{{$feature->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$feature->created_at" :updated="$feature->updated_at"/>
                                <small class="text-success">{{$feature->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$feature->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

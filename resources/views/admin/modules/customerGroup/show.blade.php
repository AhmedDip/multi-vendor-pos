@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th>@lang('ID')</th>
                            <td>{{$customerGroup->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$customerGroup->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Shop Name')</th>
                            <td>{{$customerGroup?->get_shop?->name}}</td>
                        </tr>
                        
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($customerGroup->status == \App\Models\CustomerGroup::STATUS_ACTIVE)
                                    <x-active :status="$customerGroup->status"/>
                                @else
                                    <x-inactive :status="$customerGroup->status" :title="'Inactive'"/>
                                    {{\App\Models\CustomerGroup::STATUS_LIST[$customerGroup->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$customerGroup?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$customerGroup?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$customerGroup->created_at"/>
                                <small class="text-success">{{$customerGroup->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$customerGroup->created_at" :updated="$customerGroup->updated_at"/>
                                <small class="text-success">{{$customerGroup->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 col-md-12">
                    <x-activity-log :logs="$customerGroup->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

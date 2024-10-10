@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{$address->id}}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{$address->name}}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{$address->phone}}</td>
                        </tr>
                        <tr>
                            <th>Thana</th>
                            <td>{{$address->thana->name}}</td>
                        </tr>
                        <tr>
                            <th>District</th>
                            <td>{{$address->district->name}}</td>
                        </tr>
                        <tr>
                            <th>Division</th>
                            <td>{{$address->division->name}}</td>
                        </tr>
                        <tr>
                            <th>Default Address</th>
                            <td>{{\App\Models\Address::IS_DEFAULT_LIST[$address->is_default] ?? null}}</td>
                        </tr>
                        <tr>
                            <th>Address Type</th>
                            <td>{{\App\Models\Address::ADDRESS_TYPE_LIST[$address->address_type] ?? null}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($address->status == \App\Models\Address::class::STATUS_ACTIVE)
                                    <x-active :status="$address->status"/>
                                @else
                                    <x-inactive :status="$address->status" :title="'Inactive'"/>
                                    {{\App\Models\Address::STATUS_LIST[$address->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{$address?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{$address?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>Created at</th>
                            <td>
                                <x-created-at :created="$address->created_at"/>
                                <small class="text-success">{{$address->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Updated at</th>
                            <td>
                                <x-updated-at :created="$address->created_at" :updated="$address->updated_at"/>
                                <small class="text-success">{{$address->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$address->activity_logs"/>
                </div>
            </div>
        </div>
    </div>
@endsection

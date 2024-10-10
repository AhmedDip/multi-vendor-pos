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
                            <td>{{$sales_executive->id}}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{$sales_executive?->name}}</td>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <td>
                                <img src="{{ get_image($sales_executive?->photo?->photo) }}" alt="image" class="img-thumbnail mb-2" style="height: 80px; width:80px;">
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{$sales_executive?->phone}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{$sales_executive?->email}}</td>
                        </tr>
                        <tr>
                            <th>Role Name</th>
                            <td>
                                {{-- {{$sales_executive->role->name}} --}}
                                @foreach ($sales_executive->roles as $role)
                                    {{$role?->name}}
                                @endforeach

                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($sales_executive->status == \App\Models\User::class::STATUS_ACTIVE)
                                    <x-active :status="$sales_executive->status"/>
                                @else
                                    <x-inactive :status="$sales_executive->status" :title="'Inactive'"/>
                                    {{\App\Models\User::STATUS_LIST[$sales_executive->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        
                        <tr>
                            <th>Created By</th>
                            <td>{{$sales_executive?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{$sales_executive?->updated_by?->name}}</td>
                        </tr>
                       
 
                        <tr>
                            <th>Created at</th>
                            <td>
                                <x-created-at :created="$sales_executive->created_at"/>
                                <small class="text-success">{{$sales_executive->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Updated at</th>
                            <td>
                                <x-updated-at :created="$sales_executive->created_at" :updated="$sales_executive->updated_at"/>
                                <small class="text-success">{{$sales_executive->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$sales_executive->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection
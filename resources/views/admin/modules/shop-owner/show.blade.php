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
                            <td>{{$shop_owner->id}}</td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{$shop_owner?->name}}</td>
                        </tr>
                        <tr>
                            <th>Photo</th>
                            <td>
                                <img src="{{ get_image($shop_owner?->photo?->photo) }}" alt="image" class="img-thumbnail mb-2" style="height: 80px; width:80px;">
                            </td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{$shop_owner?->phone}}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{$shop_owner?->email}}</td>
                        </tr>
                        <tr>
                            <th>Role Name</th>
                            <td>
                                @foreach ($shop_owner->roles as $role)
                                    {{$role?->name}}
                                @endforeach

                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($shop_owner->status == \App\Models\User::class::STATUS_ACTIVE)
                                    <x-active :status="$shop_owner->status"/>
                                @else
                                    <x-inactive :status="$shop_owner->status" :title="'Inactive'"/>
                                    {{\App\Models\User::STATUS_LIST[$shop_owner->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        
                        <tr>
                            <th>Created By</th>
                            <td>{{$shop_owner?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{$shop_owner?->updated_by?->name}}</td>
                        </tr>
                       
                
                        <tr>
                            <th>Created at</th>
                            <td>
                                <x-created-at :created="$shop_owner?->created_at"/>
                                <small class="text-success">{{$shop_owner?->created_at?->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Updated at</th>
                            <td>
                                <x-updated-at :created="$shop_owner?->created_at" :updated="$shop_owner?->updated_at"/>
                                <small class="text-success">{{$shop_owner?->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$shop_owner?->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

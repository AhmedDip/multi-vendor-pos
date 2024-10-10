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
                            <td>{{$shop->id}}</td>
                        </tr>
                        <tr>
                            <th>Shop Name</th>
                            <td>{{$shop->name}}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{$shop->phone}}</td>
                        </tr>
                        <tr>
                            <th>Address</th>
                            <td>{{$shop->address}}</td>
                        </tr>
                    
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($shop->status == \App\Models\Shop::STATUS_ACTIVE)
                                    <x-active :status="$shop->status"/>
                                @else
                                    <x-inactive :status="$shop->status" :title="'Inactive'"/>
                                    @lang(\App\Models\Shop::STATUS_LIST[$shop->status] ?? null)
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created By</th>
                            <td>{{$shop?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>Updated By</th>
                            <td>{{$shop?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>Created at</th>
                            <td>
                                <x-created-at :created="$shop->created_at"/>
                                <small class="text-success">{{$shop->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>Updated at</th>
                            <td>
                                <x-updated-at :created="$shop->created_at" :updated="$shop->updated_at"/>
                                <small class="text-success">{{$shop->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$shop->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

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
                            <td>{{$menu->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$menu->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Route')</th>
                            <td>{{$menu->route}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Query String')</th>
                            <td>{{$menu->query_string}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Icon')</th>
                            <td>{{$menu->icon}} {!! $menu->icon !!} </td>
                        </tr>
                        <tr>
                            <th>@lang('Sort Order')</th>
                            <td>{{$menu->sort_order}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Status')</th>
                            <td>
                                @if($menu->status == \App\Models\Menu::STATUS_ACTIVE)
                                    <x-active :status="$menu->status"/>
                                @else
                                    <x-inactive :status="$menu->status" :title="'Inactive'"/>
                                    {{\App\Models\Menu::STATUS_LIST[$menu->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Parent')</th>
                            <td>
                                {{$menu->parent_menu->parent_menu->name ?? null}}
                                @isset($menu->parent_menu->parent_menu->name)
                                    <i class="mdi mdi-arrow-right-bold-outline text-success"></i>
                                @endif
                                {{$menu->parent_menu->name ?? null}}
                                @isset($menu->parent_menu->name)
                                    <i class="mdi mdi-arrow-right-bold-outline text-success"></i>
                                @endisset
                                <strong>{{$menu->name}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created B')y</th>
                            <td>{{$menu?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$menu?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$menu->created_at"/>
                                <small class="text-success">{{$menu->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$menu->created_at" :updated="$menu->updated_at"/>
                                <small class="text-success">{{$menu->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$menu->activity_logs"/>
                </div>
            </div>
        </div>
    </div>
@endsection

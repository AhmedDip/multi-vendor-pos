@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.menu.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Route')</th>
                    <th>@lang('Query String')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($menus as $menu)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$menus"/>
                        </td>
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
                        <td>{{$menu->route}}</td>
                        <td>{{$menu->query_string}}</td>
                        <td class="text-center">{{number($menu->sort_order)}}</td>
                        <td class="text-center">
                            @if($menu->status == \App\Models\Menu::STATUS_ACTIVE)
                                <x-active :status="$menu->status"/>
                            @else
                                <x-inactive :status="$menu->status" :title="'Inactive'"/>
                                {{\App\Models\Menu::STATUS_LIST[$menu->status] ?? null}}
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$menu->created_at" :updated="$menu->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('menu.show', $menu->id)"/>
                                <x-edit-button :route="route('menu.edit', $menu->id)"/>
                                <x-delete-button :route="route('menu.destroy', $menu->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$menus"/>
        </div>
    </div>
@endsection

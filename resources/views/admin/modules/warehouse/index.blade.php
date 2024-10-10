@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.warehouse.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Phone')</th>
                    <th>@lang('Street Address')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($warehouses as $warehouse)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$warehouses"/>
                        </td>
                        <td>
                            {{$warehouse->shop?->name}}
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$warehouse->name}}</p>
                                    <p class="text-info">{{$warehouse->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{number($warehouse->sort_order)}}</td>
                        <td>{{$warehouse->phone}}</td>
                        <td>{{$warehouse->street_address}}</td>
                        <td class="text-center">
                            @if($warehouse->status == \App\Models\Warehouse::STATUS_ACTIVE)
                                <x-active :status="$warehouse->status"/>
                            @else
                                <x-inactive :status="$warehouse->status" :title="'Inactive'"/>
                                @lang(\App\Models\Warehouse::STATUS_LIST[$warehouse->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$warehouse->created_at" :updated="$warehouse->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('warehouse.show', $warehouse->id)"/>
                                <x-edit-button :route="route('warehouse.edit', $warehouse->id)"/>
                                <x-delete-button :route="route('warehouse.destroy', $warehouse->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="8"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$warehouses"/>
        </div>
    </div>
@endsection

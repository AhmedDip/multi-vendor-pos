@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.manufacturer.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($manufacturers as $manufacturer)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$manufacturers"/>
                        </td>
                        <td>{{$manufacturer->shop?->name}}</td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <img src="{{get_image($manufacturer?->photo?->photo)}}" alt="image" class="img-thumbnail table-image">
                                <div class="ms-2">
                                    <p>{{$manufacturer->name}}</p>
                                    <p class="text-info">{{$manufacturer->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{number($manufacturer->sort_order)}}</td>
                        <td class="text-center">
                            @if($manufacturer->status == \App\Models\Manufacturer::STATUS_ACTIVE)
                                <x-active :status="$manufacturer->status"/>
                            @else
                                <x-inactive :status="$manufacturer->status" :title="'Inactive'"/>
                                @lang(\App\Models\Manufacturer::STATUS_LIST[$manufacturer->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$manufacturer->created_at" :updated="$manufacturer->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('manufacturer.show', $manufacturer->id)"/>
                                <x-edit-button :route="route('manufacturer.edit', $manufacturer->id)"/>
                                <x-delete-button :route="route('manufacturer.destroy', $manufacturer->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$manufacturers"/>
        </div>
    </div>
@endsection

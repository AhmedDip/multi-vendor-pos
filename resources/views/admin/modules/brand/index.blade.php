@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.brand.partials.search')
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
                @forelse($brands as $brand)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$brands"/>
                        </td>
                        <td>
                            {{$brand->shop?->name}}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ get_image($brand?->photo?->photo)}}" alt="image"
                                        class="img-thumbnail table-image" style="max-width: 60px;">
                                    <div class="ms-2">
                                        <span>{{ $brand->name }}</span>
                                        <p class="mb-0 text-secondary"><small><strong>Slug:</strong>
                                                {{ $brand->slug }}</small></p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{number($brand->sort_order)}}</td>
                        <td class="text-center">
                            @if($brand->status == \App\Models\Brand::STATUS_ACTIVE)
                                <x-active :status="$brand->status"/>
                            @else
                                <x-inactive :status="$brand->status" :title="'Inactive'"/>
                                @lang(\App\Models\Brand::STATUS_LIST[$brand->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$brand->created_at" :updated="$brand->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('brand.show', $brand->id)"/>
                                <x-edit-button :route="route('brand.edit', $brand->id)"/>
                                <x-delete-button :route="route('brand.destroy', $brand->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="8"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$brands"/>
        </div>
    </div>
@endsection

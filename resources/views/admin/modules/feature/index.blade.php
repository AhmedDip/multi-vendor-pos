@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.feature.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($features as $feature)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$features"/>
                        </td>
                        <td>
                            {{$feature->shop?->name}}
                        </td>
                        
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p class="text">{{$feature->name}}</p>
                                    <p class="text-info">{{$feature->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$feature->sort_order}}
                        </td>
                        <td>
                            <x-date-time :created="$feature->created_at" :updated="$feature->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('feature.show', $feature->id)"/>
                                <x-edit-button :route="route('feature.edit', $feature->id)"/>
                                <x-delete-button :route="route('feature.destroy', $feature->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$features"/>
        </div>
    </div>
@endsection

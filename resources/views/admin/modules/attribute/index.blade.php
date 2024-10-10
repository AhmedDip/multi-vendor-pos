@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.attribute.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>
                        @lang('Shop Name')
                    </th>
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
                @forelse($attributes as $attribute)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$attributes"/>
                        </td>
                        <td>
                            <p>{{$attribute?->shop?->name ?? null}}</p>
                        </td> 
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$attribute->name}}</p>
                                    <p class="text-sm text-info">Slug: {{$attribute->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{number($attribute->sort_order)}}</td>
                        <td class="text-center">
                            @if($attribute->status == \App\Models\Attribute::STATUS_ACTIVE)
                                <x-active :status="$attribute->status"/>
                            @else
                                <x-inactive :status="$attribute->status" :title="'Inactive'"/>
                                @lang(\App\Models\Attribute::STATUS_LIST[$attribute->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$attribute->created_at" :updated="$attribute->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('attribute.show', $attribute->id)"/>
                                <x-edit-button :route="route('attribute.edit', $attribute->id)"/>
                                <x-delete-button :route="route('attribute.destroy', $attribute->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$attributes"/>
        </div>
    </div>
@endsection

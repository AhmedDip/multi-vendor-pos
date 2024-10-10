@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.attribute-value.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>
                        @lang('Shop Name')
                    </th>
                    <th>@lang('Attribute Name')</th>
                    <th>@lang('Value')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($attribute_values as $attribute_value)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$attribute_values"/>
                        </td>

                        <td>
                            <p>
                                {{ $attribute_value?->shop?->name ?? null }}
                            </p>
                        </td>
                        <td>
                            <p>
                                {{ $attribute_value?->attribute?->name ?? null }}
                            </p>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$attribute_value->name}}</p>
                                </div>
                            </div>
                        </td>

                        <td class="text-center">{{number($attribute_value->sort_order)}}</td>
                        <td class="text-center">
                            @if($attribute_value->status == \App\Models\AttributeValue::STATUS_ACTIVE)
                                <x-active :status="$attribute_value->status"/>
                            @else
                                <x-inactive :status="$attribute_value->status" :title="'Inactive'"/>
                                @lang(\App\Models\AttributeValue::STATUS_LIST[$attribute_value->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$attribute_value->created_at" :updated="$attribute_value->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('attribute-value.show', $attribute_value->id)"/>
                                <x-edit-button :route="route('attribute-value.edit', $attribute_value->id)"/>
                                <x-delete-button :route="route('attribute-value.destroy', $attribute_value->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="8"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$attribute_values"/>
        </div>
    </div>
@endsection

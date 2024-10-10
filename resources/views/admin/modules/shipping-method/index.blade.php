@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.shipping-method.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($shipping_methods as $shipping_method)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$shipping_methods"/>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p class="text">{{$shipping_method->name}}</p>
                                    <p class="text-info">{{$shipping_method->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <x-date-time :created="$shipping_method->created_at" :updated="$shipping_method->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('shipping-method.show', $shipping_method->id)"/>
                                <x-edit-button :route="route('shipping-method.edit', $shipping_method->id)"/>
                                <x-delete-button :route="route('shipping-method.destroy', $shipping_method->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="4"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$shipping_methods"/>
        </div>
    </div>
@endsection

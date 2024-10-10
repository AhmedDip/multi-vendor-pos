@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.discount.partials.search')
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
                @forelse($discounts as $discount)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$discounts"/>
                        </td>
                        <td>{{$discount->shop?->name}}</td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$discount->name}}</p>
                                    <p class="text-info">{{$discount->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">{{number($discount->sort_order)}}</td>
                        <td class="text-center">
                            @if($discount->status == \App\Models\Discount::STATUS_ACTIVE)
                                <x-active :status="$discount->status"/>
                            @else
                                <x-inactive :status="$discount->status" :title="'Inactive'"/>
                                @lang(\App\Models\Discount::STATUS_LIST[$discount->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$discount->created_at" :updated="$discount->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('discount.show', $discount->id)"/>
                                <x-edit-button :route="route('discount.edit', $discount->id)"/>
                                <x-delete-button :route="route('discount.destroy', $discount->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$discounts"/>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.customerGroup.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Name')</th>
                    {{-- <th>@lang('Sort Order')</th> --}}
                    <th>@lang('Shop Name')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customerGroups as $customerGroup)
                    <tr>
                        <td class="text-center">
                           <x-serial :serial="$loop->iteration" :collection="$customerGroups"/>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$customerGroup->name}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                           <p>{{$customerGroup?->get_shop?->name}}</p>
                       </td>
                        <td class="text-center">
                            @if($customerGroup->status == \App\Models\CustomerGroup::STATUS_ACTIVE)
                                <x-active :status="$customerGroup->status"/>
                            @else
                                <x-inactive :status="$customerGroup->status" :title="'Inactive'"/>
                                @lang(\App\Models\CustomerGroup::STATUS_LIST[$customerGroup->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$customerGroup->created_at" :updated="$customerGroup->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('customer-group.show', $customerGroup->id)"/>
                                <x-edit-button :route="route('customer-group.edit', $customerGroup->id)"/>
                                <x-delete-button :route="route('customer-group.destroy', $customerGroup->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="12"/> 
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$customerGroups"/>
        </div>
    </div>
@endsection

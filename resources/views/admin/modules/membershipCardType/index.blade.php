@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.membershipCardType.partials.search')
            
            {{-- :route="route('membership-card-type.show') --}}
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop Name')</th>
                    <th>@lang('Card Type Name')</th>
                    <th>@lang('Discount (%)')</th>
                    {{-- <th>@lang('Sort Order')</th> --}}
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($membershipCardTypes as $membershipCardType)
                    <tr>
                        <td class="text-center">
                           <x-serial :serial="$loop->iteration" :collection="$membershipCardTypes"/>
                        </td>
                        <td class="text-center">
                            <p>
                                @if($membershipCardType->shop)
                                    {{$membershipCardType?->shop?->name}}
                                @else
                                    <span class="text-danger">N/A</span>
                                @endif
                            </p>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$membershipCardType->card_type_name}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <p>{{$membershipCardType->discount}} % </p>
                        </td>
                    
                        <td class="text-center">
                            @if($membershipCardType->status == \App\Models\MembershipCardType::STATUS_ACTIVE)
                                <x-active :status="$membershipCardType->status"/>
                            @else
                                <x-inactive :status="$membershipCardType->status" :title="'Inactive'"/>
                                @lang(\App\Models\MembershipCardType::STATUS_LIST[$membershipCardType->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$membershipCardType->created_at" :updated="$membershipCardType->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('membership-card-type.show', $membershipCardType->id)"/>
                                <x-edit-button :route="route('membership-card-type.edit', $membershipCardType->id)"/>
                                <x-delete-button :route="route('membership-card-type.destroy', $membershipCardType->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="12"/> 
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$membershipCardTypes"/>
        </div>
    </div>
@endsection

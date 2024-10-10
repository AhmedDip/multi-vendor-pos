@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.membershipCard.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Card No.')</th>
                    <th>@lang('Membership Card Type')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($membershipCards as $membershipCard)
                    <tr>
                        <td class="text-center">
                           <x-serial :serial="$loop->iteration" :collection="$membershipCards"/>
                        </td>
                        <td class="text-center">
                            <p>{{$membershipCard?->get_shop?->name}}</p>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$membershipCard->card_no}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <p>{{$membershipCard?->membershipCardType->card_type_name}}</p>
                        </td>
                        <td class="text-center">
                            @if($membershipCard->status == \App\Models\MembershipCard::STATUS_ACTIVE)
                                <x-active :status="$membershipCard->status"/>
                            @else
                                <x-inactive :status="$membershipCard->status" :title="'Inactive'"/>
                                @lang(\App\Models\MembershipCard::STATUS_LIST[$membershipCard->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$membershipCard->created_at" :updated="$membershipCard->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('membership-card.show', $membershipCard->id)"/>
                                <x-edit-button :route="route('membership-card.edit', $membershipCard->id)"/>
                                <x-delete-button :route="route('membership-card.destroy', $membershipCard->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="12"/> 
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$membershipCards"/>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th>@lang('ID')</th>
                            <td>{{$membershipCard->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Card No')</th>
                            <td>{{$membershipCard->card_no}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Membership Card Type')</th>
                            <td>{{$membershipCard->membershipCardType->card_type_name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Shop Name')</th>
                            <td>{{$membershipCard?->get_shop?->name}}</td>
                            {{-- <p>{{$membershipCard?->get_shop?->name}}</p> --}}
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($membershipCard->status == \App\Models\MembershipCard::STATUS_ACTIVE)
                                    <x-active :status="$membershipCard->status"/>
                                @else
                                    <x-inactive :status="$membershipCard->status" :title="'Inactive'"/>
                                    {{\App\Models\MembershipCard::STATUS_LIST[$membershipCard->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$membershipCard?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$membershipCard?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$membershipCard->created_at"/>
                                <small class="text-success">{{$membershipCard->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$membershipCard->created_at" :updated="$membershipCard->updated_at"/>
                                <small class="text-success">{{$membershipCard->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 col-md-12">
                    <x-activity-log :logs="$membershipCard->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

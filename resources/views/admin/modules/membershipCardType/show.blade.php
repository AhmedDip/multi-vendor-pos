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
                            <td>{{$membershipCardType->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Membership Card Type')</th>
                            <td>{{$membershipCardType->card_type_name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Discount')</th>
                            <td>{{$membershipCardType->discount}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Shop Name')</th>
                            <td>{{$membershipCardType->shop_id}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($membershipCardType->status == \App\Models\MembershipCardType::STATUS_ACTIVE)
                                    <x-active :status="$membershipCardType->status"/>
                                @else
                                    <x-inactive :status="$membershipCardType->status" :title="'Inactive'"/>
                                    {{\App\Models\MembershipCardType::STATUS_LIST[$membershipCardType->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$membershipCardType?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$membershipCardType?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$membershipCardType->created_at"/>
                                <small class="text-success">{{$membershipCardType->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$membershipCardType->created_at" :updated="$membershipCardType->updated_at"/>
                                <small class="text-success">{{$membershipCardType->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 col-md-12">
                    <x-activity-log :logs="$membershipCardType->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

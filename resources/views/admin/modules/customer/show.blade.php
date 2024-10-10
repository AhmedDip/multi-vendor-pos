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
                            <td>{{$customer->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Name')</th>
                            <td>{{$customer->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Phone')</th>
                            <td>{{$customer->phone}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Address')</th>
                            <td>{{$customer->address}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Membership Card No')</th>
                            {{-- <td>{{$customer->membership_card_id}}</td>
                            {{$customer?->membershipCardNo->card_no}} --}}
                            <td>{{$customer?->membershipCardNo?->card_no}}</td>
                            {{-- <td>{{$customer?->membershipCardType->card_type_name}}</td> --}}
                        </tr>
                        <tr>
                            <th>@lang('Shop Name')</th>
                            {{-- <td>{{$customer->shop_id}}</td> --}}
                            <td>{{$customer?->get_shop?->name}}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($customer->status == \App\Models\Customer::STATUS_ACTIVE)
                                    <x-active :status="$customer->status"/>
                                @else
                                    <x-inactive :status="$customer->status" :title="'Inactive'"/>
                                    {{\App\Models\Customer::STATUS_LIST[$customer->status] ?? null}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>
                                {{$customer?->created_by?->name}}
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$customer?->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$customer->created_at"/>
                                <small class="text-success">{{$customer->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$customer->created_at" :updated="$customer->updated_at"/>
                                <small class="text-success">{{$customer->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 col-md-12">
                    <x-activity-log :logs="$customer->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

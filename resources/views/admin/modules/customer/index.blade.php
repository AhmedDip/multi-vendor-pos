@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.customer.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                     {{-- <th class="text-center">@lang('ID')</th> --}}
                    <th>@lang('Name')</th>
                    {{-- <th>@lang('Sort Order')</th> --}}
                    <th>@lang('Phone')</th>
                    <th>@lang('Address')</th>
                    <th>@lang('Shop Name')</th>
                    <th>@lang('Membership Card No')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                 
                </tr>
                </thead>
                <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td class="text-center">
                           <x-serial :serial="$loop->iteration" :collection="$customers"/>
                        </td>
                        {{-- <td class="text-center">
                           {{$customer->id}} 
                        </td> --}}
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$customer->name}}</p>
                                </div>
                            </div>
                        </td>
                        {{-- <td class="text-center">
                            <p>{{$customer->sort_order}}</p>
                        </td> --}}
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$customer->phone}}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$customer->address}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                           <p>{{$customer?->get_shop?->name}}</p>
                       </td>
                       <td class="text-center">
                            {{-- <p>{{$customer->membership_card_id}}</p> --}}
                            <p>{{$customer?->membershipCardNo?->card_no}}</p>
                            {{-- <p>{{$customer?->membershipCardType->card_type_name}}</p> --}}
                      
                        </td>
                        <td class="text-center">
                            @if($customer->status == \App\Models\Customer::STATUS_ACTIVE)
                                <x-active :status="$customer->status"/>
                            @else
                                <x-inactive :status="$customer->status" :title="'Inactive'"/>
                                @lang(\App\Models\Customer::STATUS_LIST[$customer->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$customer->created_at" :updated="$customer->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('customer.show', $customer->id)"/>
                                <x-edit-button :route="route('customer.edit', $customer->id)"/>
                                <x-delete-button :route="route('customer.destroy', $customer->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="12"/> 
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$customers"/>
        </div>
    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.shop.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">SL</th>
                    <th>Shop Name</th>
                    <th>Shop Owner Name</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th>Date Time
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($shops as $shop)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$shops"/>
                        </td>
                        {{-- <td>{{ $shop->name }}</td> --}}
                        
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ get_image($shop->photo?->photo) }}" alt="image"
                                        class="img-thumbnail table-image" style="max-width: 60px;">
                                    <div class="ms-2">
                                        <span>{{ $shop->name }}</span>
                                        
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                           <a href="{{ route('shop-owner.show',$shop?->shopOwner?->id) }}">
                             {{ $shop?->shopOwner?->name ?? null }}
                            </a>
                        </td>
                        <td>{{ $shop->phone }}</td>
                        <td>{{ $shop->address }}</td>
                        <td class="text-center">
                            @if($shop->status == \App\Models\Shop::STATUS_ACTIVE)
                                <x-active :status="$shop->status"/>
                            @else
                                <x-inactive :status="$shop->status" :title="'Inactive'"/>
                                @lang(\App\Models\Shop::STATUS_LIST[$shop->status] ?? null)
                            @endif
                        </td>

                       
                        <td>
                            <x-date-time :created="$shop->created_at" :updated="$shop->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('shop.show', $shop->id)"/>
                                <x-edit-button :route="route('shop.edit', $shop->id)"/>
                                <x-delete-button :route="route('shop.destroy', $shop->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="8"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$shops"/>
        </div>
    </div>
@endsection

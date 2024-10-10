@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.shop-owner.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('SL') }}</th>
                        <th>
                            {{ __('Shop Name') }}
                        </th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Image') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date Time') }}
                            <x-tool-tip :title="'C = Created at, U = Updated at'" />
                        </th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shop_owners as $shop_owner)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <ul class="ms-4">
                                    @forelse($shop_owner->shops as $shop)
                                        <li class="text-primary">
                                            <a href="{{ route('shop.show', $shop->id) }}">
                                                {{ $shop->name }}</a>
                                        </li>
                                    @empty
                                        <li class="text-danger">{{ __('N/A') }}</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td>{{ $shop_owner?->name }}</td>
                            <td>
                                <img src="{{ get_image($shop_owner?->photo?->photo) }}" alt="image" class="img-thumbnail table-image" style="max-width: 60px;">
                            </td>
                            <td>{{ $shop_owner?->email }}</td>
                            <td>{{ $shop_owner?->phone }}</td>
                            <td class="text-center">
                                @if ($shop_owner->status == \App\Models\User::STATUS_ACTIVE)
                                    <x-active />
                                @else
                                    <x-inactive />
                                @endif
                            </td>
                            <td>
                                <x-date-time :created="$shop_owner->created_at" :updated="$shop_owner->updated_at" />
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <x-view-button :route="route('shop-owner.show', $shop_owner->id)" />
                                    <x-edit-button :route="route('shop-owner.edit', $shop_owner->id)" />
                                    <x-delete-button :route="route('shop-owner.destroy', $shop_owner->id)" />
                                </div>
                            </td>
                        @empty
                            <x-data-not-found :colspan="9" />
                    @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$shop_owners" />
        </div>
    </div>
@endsection

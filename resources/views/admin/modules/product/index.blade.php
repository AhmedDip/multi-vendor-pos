@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.product.partials.search')
            <table class="table table-sm table-striped table-hover table-bordered ">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('SL') }}</th>
                        <th>{{ __('Shop Name') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>
                            {{ __('Price') }}
                        </th>
                        <th>{{ __('Sold') }}</th>
                        <th>
                            {{ __('Stock') }}
                        </th>
                        <th>
                            {{ __('Sort Order') }}
                        </th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $product?->shop?->name }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ get_image($product->photo?->photo) }}" alt="image"
                                            class="img-thumbnail table-image" style="max-width: 60px;">
                                        <div class="ms-2">
                                            <span>{{ $product->name }}</span>
                                            <p class="mb-0 text-secondary"><small><strong>Slug:</strong>
                                                    {{ $product->slug }}</small></p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        
                            <td>৳ {{ number_format($product->price, 2) }}</td>

                            <td>{{ $product->sold }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>{{ $product->sort_order }}</td>

                            <td class="text-center">
                                @if ($product->status == \App\Models\Product::STATUS_ACTIVE)
                                    <x-active />
                                @else
                                    <x-inactive />
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <x-view-button :route="route('product.show', $product->id)" />
                                    <x-edit-button :route="route('product.edit', $product->id)" />
                                    <x-delete-button :route="route('product.destroy', $product->id)" />
                                </div>
                            </td>
                        @empty
                            <x-data-not-found :colspan="9" />
                    @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$products" />
        </div>
    </div>
@endsection

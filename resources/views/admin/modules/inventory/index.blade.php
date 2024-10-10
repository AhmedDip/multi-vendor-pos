@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.inventory.partials.search')
            <table class="table table-sm table-striped table-hover table-bordered ">
                <thead>
                    <tr>
                        <th>@lang('Product Image')</th>
                        <th>@lang('Product Name')</th>
                        <th>@lang('Sku')</th>
                        <th>@lang('Barcode')</th>
                        <th>@lang('Stock')</th>
                        <th>@lang('Price')</th>
                        <th>@lang('Discount Price')</th>
                        <th>@lang('Total Sold')</th>
                        <th>@lang('Expired Date')</th>
                        <th>@lang('Edit')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventories as $inventory)
                        <tr>
                            <td><img src="{{ get_image($inventory->photo?->photo) }}" alt="image"
                            class="img-thumbnail table-image" style="max-width: 60px;"></td>
                            <td>{{ $inventory->name }}</td>
                            <td>{{ $inventory->sku }}</td>
                            <td>
                                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($inventory->sku, 'C128') }}" alt="barcode">
                            </td>
                            <td>{{ $inventory->stock }}</td>
                            <td>{{ $inventory->price }}</td>
                            <td>{{ $inventory->discount_price }}</td>
                            <td>{{ $inventory->sold }}</td>
                            <td>{{ $inventory->expiry_date }}</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <x-edit-button :route="route('product.edit', $inventory->id)" />
                                </div>
                            </td>
                        @empty
                            <x-data-not-found :colspan="9" />
                    @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$inventories" />
        </div>
    </div>
@endsection
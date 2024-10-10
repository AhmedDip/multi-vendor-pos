@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <td>{{$product->id}}</td>
                        </tr>
                        <tr>
                            <th>Shop</th>
                            <td>{{$product?->shop?->name}}</td>
                        </tr>

                        <tr>
                            <th>Type</th>
                            <td>
                              {{ $product->type}}
                            </td>
                        </tr>

                        <tr>
                            <th>Category</th>
                            <td>{{$product?->category?->name}}</td>
                        </tr>

                        <tr>
                            <th>Name</th>
                            <td>{{$product->name}}</td>
                        </tr>

                        <tr>
                            <th>Slug</th>
                            <td>{{$product->slug}}</td>
                        </tr>

                        <tr>
                            <th>Price</th>
                            <td>
                                @if ($product->discount_price)
                                    <p class="text-danger">
                                        <del>৳ {{ number_format($product->price, 2) }}</del>
                                    </p>
                                    ৳
                                    {{ number_format($product->price - $product->discount_price) }}
                                @else
                                    ৳ {{ number_format($product->price, 2) }}
                                @endif
                            </td>
                        </tr>

                       
                        <tr>
                            <th>Stock</th>
                            <td>{{$product->stock}}</td>
                        </tr>

                        <tr>
                            <th>SKU</th>
                            <td>{{$product->sku}}</td>
                        </tr>

                        <tr>
                            <th>Photos</th>
                            <td class="d-flex justify-content-center">
                                @if (isset($product))
                                    @foreach ($product->photos as $photo)
                                        <img src="{{ get_image($photo?->photo) }}" alt="image"
                                            style="width: 100px;" class="img-thumbnail table-image">
                                    @endforeach
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Brand</th>
                            <td>{{$product?->brand?->name}}</td>
                        </tr>

                        <tr>
                            <th>Description</th>
                            <td>{!! $product->description !!}</td>
                        </tr>
                      
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 mt-4">
                    <x-activity-log :logs="$product->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ public_path('admin_assets/css/media_library.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ public_path('theme/css/common-css.css') }}" rel="stylesheet" type="text/css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>{{ $title }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #EEEEEE;
            padding: 8px;
            text-align: left;
        }
        td {
            font-size: 13px;
            padding-bottom: 18px;
        }
        th {
            font-size: 14px;
            background-color: #f2f2f2;
        }
        .name-style{
            font-size: 16px;
            margin-top:10px;
        }
        .title-divider {
            display: block;
            max-width: 80px;
            height: 2px;
            background: rgba(233, 102, 49, 1);
            margin: 15px auto;
        }
        .name-style strong{
            font-size: 14px;
        }
        .name-style p{
            margin-top:6px;
        }
        .price-section{
            margin-bottom:10px;
        }
    </style>
</head>
<body>
    
    
    <h1 class="text-center" style="color:#555555">{{ $title }}</h1>
    <div class="title-divider"></div>
    
    
    <div class="pt-5 card body-card">
        <div class="card-body">
           
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    
                    <th class="text-center">{{ __('SL') }}</th>
                    <th>{{ __('Product Information') }}</th>
                    <th>
                        {{ __('Product Data Information') }}
                    </th>
                    <th>{{ __('Product Links') }}</th>
                    <th>{{ __('Product Attributes') }}</th>
                    
                    <th>{{ __('Date Time') }}
                        <x-tool-tip :title="'C = Created at, U = Updated at'" />
                    </th>
                    
                </tr>
                </thead>
                <tbody>
                    
                @foreach($products as $product)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$products"/>
                        </td>
                            
                        <td>
                            <div class="d-flex align-items-center">
                                @if($product->photo && $product->photo->photo)
                                @php
                                    $imageUrl = Storage::url($product->photo->photo);
                                @endphp
                                <img src="{{ public_path($imageUrl) }}" alt="image" class="img-thumbnail table-image" style="max-width: 40px;">
                                @endif
                                <div class="name-style">
                                    <span>{{ $product->name }}</span>
                                    <p class="mb-0 text-secondary"><small><strong>Slug:</strong> {{ $product->slug }}</small></p>
                                    {{-- @if($product->description)
                                        <p class="mb-0 text-secondary"><small><strong>Description:</strong> {{ $product->description }}</small></p>
                                    @endif --}}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="name-style">
                                <div calss="price-section">
                                    @if ($product->discount_price)
                                        <p><small><strong>Price:</strong></small>
                                            <small>
                                            <del>{{ number_format($product->price, 2) }}</del><br>
                                            
                                            {{ number_format($product->price - $product->discount_price) }}
                                            </small>
                                        </p>
                                    
                                    @else
                                        <p><small><strong>Price:</strong>{{ number_format($product->price, 2) }}</small></p>
                                    @endif
                                </div>
                                    
                                    <p class="mb-0 text-secondary"><small><strong>Sku:</strong> {{ $product->sku }}</small></p>
                                    
                                    <p class="mb-0 text-secondary"><small><strong>Type:</strong> {{ $product->type == 1 ? 'Product' : 'Service' }}</small></p>
                                    
                                    @if ($product->type != 1)
                                        <p class="mb-0 text-secondary"><small><strong>Duration:</strong> {{ $product->duration }}min</small></p>
                                    @else
                                        <p class="mb-0 text-success" ><small><strong>Expiry Date:</strong><span style="color:#198754">{{ \Carbon\Carbon::parse($product->expiry_date)->format('l, d F, Y h:i a') }}</span></small></p>
                                    @endif
                                    
                                   
                                    
                                    <p class="mb-0 text-secondary"><small><strong>Stock:</strong> 
                                        @if ($product->stock < 1)
                                            
                                            <span style="color:red">out of stock</span>
                                        @else
                                            <span>{{ $product->stock }}</span>
                                        @endif
                                    
                                        </small>
                                    </p>
                                    
                                    <p class="mb-0 text-secondary"><small><strong>Status:</strong> {{ $product->status == 1 ? 'Active' : 'Inactive' }}</small></p>
                            </div>
                           
                        </td>
                            
                        
                        
                        <td>
                            <div class="d-flex align-items-center">
                                
                                <div class="name-style">
                                    <p class="mb-0 text-secondary"><small><strong>Shop:</strong> {{ $product?->shop?->name }}</small></p>
                                    <p class="mb-0 text-secondary"><small><strong>Category:</strong> {{ $product?->category?->name }}</small></p>
                                    @if($product->brand)
                                    
                                        <p class="mb-0 text-secondary"><small><strong>Brand:</strong> {{ $product->brand?->name }}</small></p>
                                    @endif
                                    
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $attributes = $product->attributeValues ? $product->attributeValues->pluck('name')->implode(', ') : '';
                            @endphp
                            {{ $attributes }}
                        </td>
                       
                        <td>
                            <x-date-time :created="$product->created_at" :updated="$product->updated_at"/>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

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
                    <th>{{ __('Product Details') }}</th>
                    <th>{{ __('Barcode') }}</th>
                    <th>{{ __('Stock') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Sold') }}</th>
                    <th>{{ __('Expair date') }}</th>
                    
                </tr>
                </thead>
                <tbody>
                    
                @foreach($inventories as $inventory)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$inventories"/>
                        </td>
                            
                        <td>
                            <div class="d-flex align-items-center">
                                @if($inventory->photo && $inventory->photo->photo)
                                    @php
                                        $imageUrl = Storage::url($inventory->photo->photo);
                                    @endphp
                                    <img src="{{ public_path($imageUrl) }}" alt="image" class="img-thumbnail table-image" style="max-width: 40px;">
                                    <div class="name-style">
                                        <span>{{ $inventory->name }}</span>
                                        <p class="mb-0 text-secondary"><small><strong>Sku:</strong> {{ $inventory->sku }}</small></p>
                                    </div>
                                   
                                @endif
                                
                            </div>
                        </td>
                        <td>
                            <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($inventory->sku, 'C128') }}" alt="barcode" style="height:25px">
                        </td>
                        <td>
                            <p class="mb-0 text-center text-secondary" >
                                @if ($inventory->stock < 1)
                                    
                                    <span style="color:red">out of stock</span>
                                @else
                                    <span>{{ $inventory->stock }}</span>
                                @endif
                            
                            </p>
                        </td>
                        <td>
                            <div class="name-style">
                                <div calss="price-section text-center">
                                    @if ($inventory->discount_price)
                                        <p>
                                            <small>
                                            <del>{{ number_format($inventory->price, 2) }}</del><br>
                                            
                                            {{ number_format($inventory->price - $inventory->discount_price) }}
                                            </small>
                                        </p>
                                    @else
                                        <p><small>{{ number_format($inventory->price, 2) }}</small></p>
                                    @endif
                                </div>
                            </div>
                           
                        </td>
                            
                       
                        <td class="text-center">
                            {{$inventory->sold }}
                        </td>
                        <td>
                            <p class="mb-0 text-success" ><small><span style="color:#198754">{{ \Carbon\Carbon::parse($inventory->expiry_date)->format('l, d F, Y h:i a') }}</span></small></p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

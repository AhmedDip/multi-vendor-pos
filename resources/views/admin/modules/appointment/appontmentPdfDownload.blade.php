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
    <div class="pt-5">
        <table class="table table-striped table-hover table-bordered ">
            <thead>
                <tr>
                    <th class="text-center">{{ __('Invoice No') }}</th>
                    <th>{{ __('Shop Name') }}</th>
                    <th>{{ __('Order Date') }}</th>
                    <th>{{ __('Customer') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Services') }}</th>
                    <th>{{ __('Payment Status') }}</th>
                    <th>{{ __('Payment Information') }}</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td class="text-center" style="font-size: 12px;">{{ $appointment->invoice_number }}</a>
                        </td>
                       

                        <td>{{ $appointment?->get_shop?->name }}</td>

                        <td style="font-size: 12px;">{{ \Carbon\Carbon::parse($appointment->date)->format('l, d F, Y,  h:i a') }}</td>

                        <td style="font-size: 12px;"> 
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-user"></i>
                                    <div class="ms-2" style="line-height:20px">
                                        <span>{{ $appointment->name }}</span>
                                        <p class="mb-0 text-secondary"><small><strong>Phone:</strong>
                                        {{ $appointment->phone }}</small></p>
                                                        
                                        @if ($appointment->email)
                                            <p class="mb-0 text-secondary"><small><strong>Email:</strong>
                                            {{ $appointment->email }}</small></p>     
                                        @endif
                                                
                                        @if ($appointment->message)
                                            <p class="mb-0 text-secondary"><small><strong>Note:</strong>
                                            {{ $appointment->message }}</small></p>     
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            {{ $appointment?->get_category?->name }}
                        </td>

                        <td style="font-size: 11px;">
                            <ol>
                                @foreach($appointment->services as $service)
                                <li class="ms-2">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="ms-1">
                                                <span>{{ $service->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                                    
                            </ol>
                        </td>

                        <td style="font-size: 12px;"> 
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-user"></i>
                                    <div class="ms-2">
                                         <span class="badge bg-dark">
                                            {{ $appointment?->transaction?->payment_status ===1 ? 'Paid':'Unpaid' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size: 12px;"> 
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-user"></i>
                                    <div class="ms-2" style="line-height:20px;">
                                        <p class="mb-0 text-secondary"><small><strong>Total:</strong>
                                            {{ number_format($appointment->amount,2)}} Tk</small></p>
                                                        
                                                        
                                        @if ($appointment?->transaction && $appointment?->transaction?->discount )
                                            <p class="mb-0 text-secondary"><small><strong>Discount:</strong>
                                            {{  number_format($appointment?->transaction?->discount,2) }} Tk</small></p>     
                                        @endif
                                                
                                        @if ($appointment?->transaction && $appointment?->transaction?->discount )
                                            <p class="mb-0 text-secondary"><small><strong>Total Payable:</strong>
                                            {{  number_format($appointment?->transaction?->total_payable_amount ,2)}} Tk</small></p>     
                                        @endif
                                                
                                        <p class="mb-0 text-secondary"><small><strong>Total paid:</strong>
                                        {{ number_format($appointment?->transaction?->total_paid_amount,2)}} Tk</small></p>
                                                    
                                        @if ($appointment?->transaction && $appointment?->transaction?->total_due_amount )
                                            <p class="mb-0 text-secondary"><small><strong>Total Payable:</strong>
                                            {{  number_format($appointment?->transaction?->total_due_amount,2) }} Tk</small></p>  
                                        @endif
                                                
                                    </div>
                                </div>
                            </div>
                        </td>
                            
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
      
</body>
</html>

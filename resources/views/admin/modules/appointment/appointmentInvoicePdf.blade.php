
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ public_path('admin_assets/css/media_library.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ public_path('theme/css/common-css.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   
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
        .name-style {
            font-size: 16px;
            margin-top: 10px;
        }
        .title-divider {
            display: block;
            max-width: 80px;
            height: 2px;
            background: rgba(233, 102, 49, 1);
            margin: 15px auto;
        }
        .name-style strong {
            font-size: 14px;
        }
        .name-style p {
            margin-top: 6px;
        }
        .price-section {
            margin-bottom: 10px;
        }
        .page-break {
            page-break-after: always; 
        }
        .invoice-header-table {
            border: none;
        }
        .margin-style {
            margin-bottom: 5px;
        }
        .amount-total-border-none {
            border: none;
        }
        .transection-style {
            text-align: end;
            padding: 5px 8px; 
        }
    </style>
</head>
<body>
    <div class="pt-5 card body-card">
        <div class="card-body">
            @foreach ($appointments as $index => $appointment)
                <h1 class="text-center" style="color: #555555">Invoice</h1>
                <div class="title-divider"></div>
                
                <table class="invoice-header-table">
                    <tr>
                        <td style="border:none">
                            <h4>Invoice # {{$appointment->invoice_number}}</h4>
                            <p style="margin-bottom:5px;"><strong>Customer Information</strong></p>
                            
                            <ul class="list-unstyled margin-style" style="line-height:20px">
                                <li><strong>Name:</strong> {{ $appointment->name }}</li>
                                <li><strong>Phone:</strong> {{ $appointment->phone }}</li>
                                {{-- <li><strong>Address:</strong> {{ $appointment?->customer?->address }}</li> --}}
                                <li><strong>Payment method:</strong> {{ $appointment?->transaction?->payment_type === 1 ? 'Cash' : 'Online Payment' }}</li>
                              
                                @if ($appointment && $appointment?->transaction && $appointment?->transaction?->payment_type != 1)
                                    <li><strong>Trx No: </strong>{{ $appointment?->transaction?->trx_id}}</li>
                                    <li><strong>Sender number: </strong>{{ $appointment?->transaction?->sender_number}}</li>
                                @endif 
                                    
                            </ul>
                        </td>
                        <td style="border:none">
                            <ul class="list-unstyled">
                                <li><h1>{{ $appointment?->get_shop?->name }}</h1></li>
                                <li><small>{{ $appointment?->get_shop?->address }}</small></li>
                                <li><strong>Phone:</strong> {{ $appointment?->get_shop?->phone }}</li>
                                <li><strong>Email:</strong> {{ $appointment?->get_shop?->email }}</li>
                            </ul>
                        </td>
                    </tr>
                </table>
           
                <table class="table table-striped table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">{{ __('SL') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Service Name') }}</th>
                            <th>{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($appointment->services as $item)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>{{ $item?->category?->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="name-style">
                                            <span>{{ $item->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                
                                <td>{{ number_format($item->price, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="amount-total-border-none" colspan="2"></td>
                            <th>Total Amount</th>
                            <td class="transection-style">{{ $appointment->amount ? number_format($appointment->amount, 2) : '0' }}Tk</td>
                        </tr>

                        <tr>
                            <td class="amount-total-border-none" colspan="2"></td>
                            <th>Discount</th>
                            <td class="transection-style">{{ $appointment?->transaction?->discount ? number_format($appointment?->transaction?->discount, 2) : '0' }}Tk</td>
                        </tr>
                        <tr>
                            <td class="amount-total-border-none" colspan="2"></td>
                            <th>Total Payable</th>
                            <td class="transection-style">{{ $appointment->transaction ? number_format($appointment?->transaction?->total_payable_amount, 2) : '0' }}Tk</td>
                        </tr>
                        <tr>
                            <td class="amount-total-border-none" colspan="2"></td>
                            <th>Total Paid</th>
                            <td class="transection-style">{{ $appointment->transaction ? number_format($appointment?->transaction?->total_paid_amount, 2) : '0' }}Tk</td>
                        </tr>
                        <tr>
                            <td class="amount-total-border-none" colspan="2"></td>
                            <th>Due</th>
                            <td class="transection-style">{{ $appointment->transaction ? number_format($appointment?->transaction?->total_due_amount, 2) : '0' }}Tk</td>
                        </tr>
                    </tbody>
                </table>
                
                @if ($index < count($appointments) - 1)
                    <div class="page-break"></div>
                @endif
                {{-- @if (!$loop->last)
                    <div class="page-break"></div>
                @endif --}}
                
            @endforeach
        </div>
    </div>
</body>
<footer><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script></footer>
</html>



 

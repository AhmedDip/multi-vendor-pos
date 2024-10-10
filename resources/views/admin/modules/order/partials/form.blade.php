<div class="row">
    <div class="col-md-6 mb-2">
        <div class="card">
            <div class="card-head">
                {{ __('Customer Information') }}
            </div>

            <div class="card-body">
                <div class="custom-form-group">
                    <label for="shop_id">{{ __('Shop') }}</label>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-store"></i></span>
                        </div>
                        {{ html()->select('shop_id', $shops, $shopId ?? null)->id('shop_id')->class('form-control form-control-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder('Select Shop') }}
                    </div>

                    <x-validation-error :errors="$errors->first('shop_id')" />
                </div>

                <div class="custom-form-group">
                    <label for="phone">{{ __('Customer Phone Number') }}</label>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        {{-- {{ html()->text('phone')->id('phone')->class('form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''))->placeholder('Enter phone number') }} --}}
                        {{ html()->text('phone', old('phone', $order?->customer?->phone ?? $customer_phone))->id('phone')->class('form-control form-control-sm')->placeholder('Enter phone number') }}
                    </div>
                    <x-validation-error :errors="$errors->first('phone')" />
                </div>

                <div class="custom-form-group">
                    <label for="name">{{ __('Customer Name') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        {{ html()->text('name', old('name', $order?->customer?->name ?? $customer_name))->id('name')->class('form-control form-control-sm')->placeholder('Enter customer name') }}
                    </div>
                    <x-validation-error :errors="$errors->first('name')" />
                </div>

                <div class="custom-form-group">
                    <label for="order_date">{{ __('Order Date') }}</label>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        </div>
                        {{ html()->datetime('order_date', old('order_date', $order?->order_date ?? null))->id('order_date')->class('form-control form-control-sm')->placeholder('Enter order date') }}
                    </div>
                    <x-validation-error :errors="$errors->first('order_date')" />
                </div>

                <div class="custom-form-group">
                    <label for="status">{{ __('Order Status') }}</label>
                    <span class="text-danger">*</span>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                        </div>
                        {{ html()->select('status', \App\Models\Order::STATUS_LIST, old('status', $order->status ?? null))
                            ->id('status')
                            ->class('form-control form-control-sm')
                            ->placeholder('Select Order Status') }}
                    </div>
                    <x-validation-error :errors="$errors->first('status')" />
                </div>
                

                <div class="custom-form-group">
                    <label for="invoice_number">{{ __('Invoice Number') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                        </div>
                        <p class="form-control form-control-sm text-success">{{ $order?->invoice_number ?? $invoice_number }}</p>
                    </div>
                    <x-validation-error :errors="$errors->first('invoice_number')" />
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-6 mb-2">
        <div class="card">
            <div class="card-head">
                {{ __('Order Items') }}
            </div>
            <div class="card-body">
                <div class="custom-form-group">
                    <label for="product_id">{{ __('Product') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-box"></i></span>
                        </div>
                        <select name="product_id" id="product_id" class="form-control form-control-sm">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                data-shop-id="{{ $product->shop_id }}"
                                @if (isset($productId) && $productId == $product->id) selected @endif
                                >
                                {{ $product->name }}
                            </option>
                        @endforeach
                        
                        </select>

                    </div>
                    <x-validation-error :errors="$errors->first('product_id')" />
                </div>

                <div class="custom-form-group">
                    <label for="quantity">{{ __('Quantity') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                        </div>
                        {{ html()->number('quantity')->id('quantity')->class('form-control form-control-sm' . ($errors->has('quantity') ? 'is-invalid' : ''))->placeholder('Enter Quantity')}}
                    </div>
                    <x-validation-error :errors="$errors->first('quantity')" />
                </div>

                <div class="custom-form-group">
                    <label for="unit_price">{{ __('Unit Price') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                        </div>
                        {{ html()->text('unit_price')->id('unit_price')->class('form-control form-control-sm' . ($errors->has('unit_price') ? 'is-invalid' : ''))->placeholder('Enter Unit Price') }}
                    </div>
                    <x-validation-error :errors="$errors->first('unit_price')" />
                </div>

                <div class="custom-form-group">
                    <label for="total_price">{{ __('Total Price') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                        </div>
                        {{ html()->text('total_price')->id('total_price')->class('form-control form-control-sm' . ($errors->has('total_price') ? 'is-invalid' : ''))->placeholder('Enter Total Price') }}
                    </div>
                    <x-validation-error :errors="$errors->first('total_price')" />
                </div>

                <div class="text-center mt-3">
                    <button type="button" class="btn btn-sm btn-dark" id="add_product">
                        <i class="fas fa-plus"></i> {{ __('Add Product') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-head">
                {{ __('Selected Products') }}
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('Product') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Unit Price') }}</th>
                            <th>{{ __('Total Price') }}</th>
                            <th>{{ __('Assigned To') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody id="product_list">
                        @isset($order->items)
                            @foreach ($order->items as $orderItem)
                                <tr>
                                    <td>
                                        {{ $orderItem->product->name }}
                                        <input type="hidden" name="product_id[]" value="{{ $orderItem->product_id }}">
                                    </td>
                                    <td>
                                        <input type="number" name="quantity[]" value="{{ $orderItem->quantity }}"
                                            class="form-control form-control-sm quantity-input">
                                    </td>
                                    <td>
                                        {{ $orderItem->unit_price }}
                                        <input type="hidden" name="unit_price[]" value="{{ $orderItem->unit_price }}"
                                            class="unit-price-input">
                                    </td>
                                    <td>
                                        <span class="total-price">{{ $orderItem->total_price }}</span>
                                        <input type="hidden" name="total_price[]" value="{{ $orderItem->total_price }}"
                                            class="total-price-input">
                                    </td>
                                    <td>
                                        <select name="assign_to[]" class="form-select form-select-sm">
                                            <option value="">Select Assign To</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ $orderItem->assign_to == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger remove_product">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                        

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-head">{{ __('Payment Details') }}</div>
            <div class="card-body">
                <div class="custom-form-group">
                    <label for="total_amount">{{ __('Total Amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        </div>
                        {{ html()->text('total_amount', old('total_amount', $order->total_amount ?? null))->id('total_amount')->class('form-control form-control-sm')->placeholder('Enter Total Amount') }}
                    </div>
                    <x-validation-error :errors="$errors->first('total_amount')" />
                </div>

                <div class="custom-form-group">
                    <label for="discount_percentage">{{ __('Discount Percentage') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                        </div>
                        <input type="text" id="discount_percentage" class="form-control form-control-sm" value="{{ $order->discount_percentage }}">
                    </div>
                </div>

                <div class="custom-form-group">
                    <label for="discount_amount">{{ __('Discount Amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-tags"></i></span>
                        </div>
                        {{ html()->text('discount_amount', old('discount_amount', $order->discount_amount ?? null))->id('discount_amount')->class('form-control form-control-sm')->placeholder('Enter Discount Amount') }}
                    </div>
                    <x-validation-error :errors="$errors->first('discount_amount')" />
                </div>

                <div class="custom-form-group">
                    <label for="total_payable_amount">{{ __('Total Payable Amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-coins"></i></span>
                        </div>
                        {{ html()->text('total_payable_amount', old('total_payable_amount', $order->total_payable_amount ?? null))->id('total_payable_amount')->class('form-control form-control-sm')->placeholder('Enter Payable Amount') }}
                    </div>
                    <x-validation-error :errors="$errors->first('total_payable_amount')" />
                </div>

                <div class="custom-form-group">
                    <label for="total_paid_amount">{{ __('Total Paid Amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                        </div>
                        <input type="text" id="total_paid_amount" class="form-control form-control-sm" value="{{ $order->total_paid_amount }}" readonly>
                    </div>
                </div>

                <div class="custom-form-group">
                    <label for="total_due_amount">{{ __('Total Due Amount') }}</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
                        </div>
                        <input type="text" id="total_due_amount" class="form-control form-control-sm" value="{{ $order->total_due_amount }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-head">{{ __('Payment Transactions') }}</div>
            <div class="card-body">
                <div id="transactions-container">
                    <div class="transaction-form">
                        <div class="custom-form-group">
                            <label for="payment_method_id">{{ __('Payment Method') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                </div>
                                {{ html()->select('payment_method_id[]', $payment_methods)->id('payment_method_id')->class('form-control form-control-sm')->placeholder('Select Payment Method') }}
                            </div>
                        </div>
    
                        <div class="custom-form-group">
                            <label for="amount">{{ __('Amount') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                {{ html()->text('amount[]', null)->id('amount')->class('form-control form-control-sm')->placeholder('Enter Amount') }}
                            </div>
                        </div>
    
                        <div class="custom-form-group">
                            <label for="sender_account">{{ __('Sender Number') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                {{ html()->text('sender_account[]', null)->id('sender_account')->class('form-control form-control-sm')->placeholder('Enter Sender Number') }}
                            </div>
                        </div>
    
                        <div class="custom-form-group">
                            <label for="trx_id">{{ __('Transaction ID') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                {{ html()->text('trx_id[]', null)->id('trx_id')->class('form-control form-control-sm')->placeholder('Enter Transaction ID') }}
                            </div>
                        </div>
    
                        <div class="custom-form-group">
                            <label for="payment_status">{{ __('Payment Status') }}</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check"></i></span>
                                </div>
                                {{ html()->select('payment_status[]', \App\Models\Order::PAYMENT_STATUS_LIST)->id('payment_status')->class('form-control form-control-sm')->placeholder('Select Payment Status') }}
                            </div>
                        </div>
    
                        <hr>
                        <button type="button" class="btn btn-sm btn-danger remove-transaction">
                            <i class="fas fa-trash"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" id="add-transaction">
                            <i class="fas fa-plus"></i>
                        </button>
                        <hr>
                    </div>
                </div>
    
               
            </div>
        </div>
    </div>
</div>


<div class="row mt-3 mb-3">
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-head">{{ __('Transaction History') }}</div>
            <div class="card-body">
                @if($order->transactions->isEmpty())
                    <p>{{ __('No transactions found for this order.') }}</p>
                @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('Payment Method') }}</th>
                                <th>{{ __('Sender Number') }}</th>
                                <th>{{ __('Transaction ID') }}</th>
                                <th>{{ __('Payment Status') }}</th>
                                   <th>{{ __('Amount') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction?->payment_method?->name }}</td>
                                    <td>
                                        {{ $transaction->sender_account ?? '--' }}
                                    </td>
                                    <td>
                                        {{ $transaction->trx_id ?? '--' }}
                                    </td>
                                    <td>{{ \App\Models\Order::PAYMENT_STATUS_LIST[$transaction->payment_status] }}</td>
                                    <td>{{ $transaction?->amount }} ৳</td>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('jS F, Y g:iA') }}</td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>{{ __('Total Paid Amount') }}</strong></td>
                                <td class="text-success">
                                    <strong>{{ $order->transactions->sum('amount') }} ৳</strong>
                                </td>
                            </tr>
                        
                        </tfoot>
                    </table>
                @endif
            </div>
        </div>
    </div>
    

    <div class="col-12">
        <div class="card">
            <div class="card-head">{{ __('Note') }}</div>
            <div class="card-body">
                <div class="custom-form-group">
                    {{ html()->textarea('note')->class('form-control form-control-sm')->placeholder('Enter Note') }}
                </div>
            </div>
        </div>
    </div>
</div>

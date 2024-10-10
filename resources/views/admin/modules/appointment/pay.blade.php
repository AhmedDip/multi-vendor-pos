@extends('admin.layouts.app')
@section('content')
    <div class="card">
        <form action="{{ route('appointment.processPayment', $appointment->id) }}" method="POST">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">{{ __('Transaction Details') }}</div>
                        <div class="card-body">
                            <!-- Transaction Details Fields -->
                            <div class="custom-form-group">
                                <label for="payment_type">{{ __('Payment Type') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                    </div>
                                    {{ html()->select('payment_type', \App\Models\Order::PAYMENT_TYPE_LIST, old('payment_type', $appointment->transaction->payment_type ?? null))->id('payment_type')->class('form-control form-control-sm')->placeholder('Select Payment Type') }}
                                </div>
                                <x-validation-error :errors="$errors->first('payment_type')" />
                            </div>

                            <div class="custom-form-group" id="payment_method_id_group">
                                <label for="payment_method_id">{{ __('Payment Method') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    </div>
                                    {{ html()->select('payment_method_id', $payment_methods, old('payment_method_id', $appointment->transaction->payment_method_id ?? null))->id('payment_method_id')->class('form-control form-control-sm')->placeholder('Select Payment Method') }}
                                </div>
                                <x-validation-error :errors="$errors->first('payment_method_id')" />
                            </div>

                            <div class="custom-form-group" id="sender_number_group">
                                <label for="sender_number">{{ __('Sender Number') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    {{ html()->text('sender_number', old('sender_number', $appointment->transaction->sender_number ?? null))->id('sender_number')->class('form-control form-control-sm')->placeholder('Enter Sender Number') }}
                                </div>
                                <x-validation-error :errors="$errors->first('sender_number')" />
                            </div>

                            <div class="custom-form-group" id="trx_id_group">
                                <label for="trx_id">{{ __('Transaction ID') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                    </div>
                                    {{ html()->text('trx_id', old('trx_id', $appointment->transaction->trx_id ?? null))->id('trx_id')->class('form-control form-control-sm')->placeholder('Enter Transaction ID') }}
                                </div>
                                <x-validation-error :errors="$errors->first('trx_id')" />
                            </div>

                            <div class="custom-form-group">
                                <label for="payment_status">{{ __('Payment Status') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                    </div>
                                    {{ html()->select('payment_status', \App\Models\Order::PAYMENT_STATUS_LIST, old('payment_status', $appointment->transaction->payment_status ?? null))->id('payment_status')->class('form-control form-control-sm')->placeholder('Select Payment Status') }}
                                </div>
                                <x-validation-error :errors="$errors->first('payment_status')" />
                            </div>

                            <div class="custom-form-group">
                                <label for="order_status">{{ __('Order Status') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tasks"></i></span>
                                    </div>
                                    {{ html()->select('order_status', \App\Models\Order::ORDER_STATUS_LIST, old('order_status', $appointment->transaction->order_status ?? null))->id('order_status')->class('form-control form-control-sm')->placeholder('Select Order Status') }}
                                </div>
                                <x-validation-error :errors="$errors->first('order_status')" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="card">
                        <div class="card-header">{{ __('Payment Details') }}</div>
                        <div class="card-body">
                            <div class="custom-form-group">
                                <label for="total_amount">{{ __('Total Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="text" id="total_amount" name="amount" class="form-control form-control-sm" placeholder="Enter Total Amount" value="{{ old('amount', $appointment->amount ?? $totalAmount) }}">
                                </div>
                                <x-validation-error :errors="$errors->first('total_amount')" />
                            </div>
                            <div class="custom-form-group">
                                <label for="discount_percentage">{{ __('Discount Percentage') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                    </div>
                                    <input type="text" id="discount_percentage" name="discount_percentage" class="form-control form-control-sm" placeholder="Enter Discount Percentage" value="{{ old('discount_percentage', $appointment->transaction->discount_percentage ?? $discount_percentage) }}" oninput="calculateDiscount()">
                                </div>
                                <x-validation-error :errors="$errors->first('discount_percentage')" />
                            </div>

                            <div class="custom-form-group">
                                <label for="discount_amount">{{ __('Discount Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                    </div>
                                    <input type="text" id="discount_amount" name="discount" class="form-control form-control-sm" placeholder="Enter Discount Amount" value="{{ old('discount', $appointment->transaction->discount ?? null) }}" readonly>
                                </div>
                                <x-validation-error :errors="$errors->first('discount_amount')" />
                            </div>

                            <div class="custom-form-group">
                                <label for="total_payable_amount">{{ __('Total Payable Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-money-check-alt"></i></span>
                                    </div>
                                    <input type="text" id="total_payable_amount" name="total_payable_amount" class="form-control form-control-sm" placeholder="Enter Payable Amount" value="{{ old('total_payable_amount', $appointment->transaction->total_payable_amount ?? $totalAmount) }}" readonly>
                                </div>
                                <x-validation-error :errors="$errors->first('total_payable_amount')" />
                            </div>

                            <div class="custom-form-group">
                                <label for="total_paid_amount">{{ __('Total Paid Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-wallet"></i></span>
                                    </div>
                                    <input type="text" id="total_paid_amount" name="total_paid_amount" class="form-control form-control-sm" placeholder="Enter Paid Amount" value="{{ old('total_paid_amount', $appointment->transaction->total_paid_amount ?? null) }}" oninput="calculateDiscount()">
                                </div>
                                <x-validation-error :errors="$errors->first('total_paid_amount')" />
                            </div>

                            <div class="custom-form-group">
                                <label for="total_due_amount">{{ __('Total Due Amount') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-hand-holding-usd"></i></span>
                                    </div>
                                    <input type="text" id="total_due_amount" name="total_due_amount" class="form-control form-control-sm" placeholder="Enter Due Amount" value="{{ old('total_due_amount', $appointment->transaction->total_due_amount ?? null) }}" readonly>
                                </div>
                                <x-validation-error :errors="$errors->first('total_due_amount')" />
                            </div>
                        </div>
                    </div>
                </div>   
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i> {{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentTypeElement = document.getElementById('payment_type');
        const senderNumberGroup = document.getElementById('sender_number_group');
        const trxIdGroup = document.getElementById('trx_id_group');
        const paymentMethodElement = document.getElementById('payment_method_id_group');

        function toggleFields() {
            const paymentType = paymentTypeElement.value;
            senderNumberGroup.style.display = paymentType === '2' ? 'block' : 'none';
            trxIdGroup.style.display = paymentType === '2' ? 'block' : 'none';
            paymentMethodElement.style.display = paymentType === '2' ? 'block' : 'none';
        }

        toggleFields();
        paymentTypeElement.addEventListener('change', toggleFields);

        // Calculate discount initially
        calculateDiscount();
    });

    function calculateDiscount() {
        let totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
        let discountPercentage = parseFloat(document.getElementById('discount_percentage').value) || 0;
        let discountAmount = (totalAmount * discountPercentage) / 100;
        document.getElementById('discount_amount').value = discountAmount.toFixed(2);

        let totalPayableAmount = totalAmount - discountAmount;
        document.getElementById('total_payable_amount').value = totalPayableAmount.toFixed(2);

        let totalPaidAmount = parseFloat(document.getElementById('total_paid_amount').value) || 0;
        let totalDueAmount = totalPayableAmount - totalPaidAmount;
        document.getElementById('total_due_amount').value = totalDueAmount.toFixed(2);
    }

    document.getElementById('discount_percentage').addEventListener('input', calculateDiscount);
    document.getElementById('total_paid_amount').addEventListener('input', calculateDiscount);
</script>
@endpush

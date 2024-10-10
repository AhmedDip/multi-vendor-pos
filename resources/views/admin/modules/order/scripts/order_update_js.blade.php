@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shopSelect = document.getElementById('shop_id');
            const productSelect = document.getElementById('product_id');
            const quantityInput = document.getElementById('quantity');
            const unitPriceInput = document.getElementById('unit_price');
            const totalPriceInput = document.getElementById('total_price');
            const productList = document.getElementById('product_list');
            const addProductButton = document.getElementById('add_product');
            const totalAmountInput = document.getElementById('total_amount');
            const discountPercentageInput = document.getElementById('discount_percentage');
            const discountAmountInput = document.getElementById('discount_amount');
            const totalPayableAmountInput = document.getElementById('total_payable_amount');
            const totalPaidAmountInput = document.getElementById('total_paid_amount');
            const totalDueAmountInput = document.getElementById('total_due_amount');

            const productPrices = @json($products->pluck('price', 'id'));

            function calculateTotalAmount() {
                let totalAmount = 0;
                document.querySelectorAll('.total-price-input').forEach(function(input) {
                    totalAmount += parseFloat(input.value) || 0;
                });
                totalAmountInput.value = totalAmount.toFixed(2);
                calculateTotalPayable();
            }

            function calculateTotalPayable() {
                const totalAmount = parseFloat(totalAmountInput.value) || 0;
                const discountPercentage = parseFloat(discountPercentageInput.value) || 0;
                let discountAmount = parseFloat(discountAmountInput.value) || 0;
                let totalPayable = totalAmount;

                if (discountPercentage > 0) {
                    discountAmount = totalAmount * (discountPercentage / 100);
                    discountAmountInput.value = discountAmount.toFixed(2);
                    totalPayable -= discountAmount;
                } else if (discountAmount > 0) {
                    totalPayable -= discountAmount;
                }
                totalPayableAmountInput.value = totalPayable.toFixed(2);
                calculateTotalDueAmount();
            }

            function calculateTotalDueAmount() {
                const totalPayable = parseFloat(totalPayableAmountInput.value) || 0;
                const totalPaid = parseFloat(totalPaidAmountInput.value) || 0;
                const totalDue = totalPayable - totalPaid;
                totalDueAmountInput.value = totalDue.toFixed(2);
                totalDueAmountInput.style.color = totalDue > 0 ? 'red' : 'green';
            }

            function updateTotalPrice() {
                const quantity = parseFloat(quantityInput.value) || 0;
                const unitPrice = parseFloat(unitPriceInput.value) || 0;
                totalPriceInput.value = (quantity * unitPrice).toFixed(2);
            }

            function addProductToTable(productId, productName, quantity, unitPrice, totalPrice) {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${productName}<input type="hidden" name="product_id[]" value="${productId}"></td>
                <td><input type="number" name="quantity[]" value="${quantity}" class="form-control form-control-sm quantity-input"></td>
                <td>${unitPrice}<input type="hidden" name="unit_price[]" value="${unitPrice}" class="unit-price-input"></td>
                <td><span class="total-price">${totalPrice}</span><input type="hidden" name="total_price[]" value="${totalPrice}" class="total-price-input"></td>
                <td>
                    <select name="assign_to[]" class="form-select form-select-sm">
                        <option value="">Select Assign To</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><button type="button" class="btn btn-sm btn-danger remove_product"><i class="fas fa-trash"></i></button></td>
            `;
                productList.appendChild(row);
                calculateTotalAmount();
            }

            function resetFormFields() {
                productSelect.value = '';
                quantityInput.value = '';
                unitPriceInput.value = '';
                totalPriceInput.value = '';
            }

            shopSelect.addEventListener('change', function() {
                const selectedShopId = this.value;
                [...productSelect.options].forEach(option => {
                    if (option.value) {
                        option.style.display = option.getAttribute('data-shop-id') ===
                            selectedShopId ? 'block' : 'none';
                    }
                });
                resetFormFields();
            });

            productSelect.addEventListener('change', function() {
                const selectedProductId = this.value;
                const unitPrice = productPrices[selectedProductId] || 0;
                unitPriceInput.value = unitPrice;
                updateTotalPrice();
            });

            quantityInput.addEventListener('input', updateTotalPrice);

            addProductButton.addEventListener('click', function() {
                const productId = productSelect.value;
                const productName = productSelect.options[productSelect.selectedIndex].text;
                const quantity = quantityInput.value;
                const unitPrice = unitPriceInput.value;
                const totalPrice = totalPriceInput.value;

                if (productId && quantity && unitPrice && totalPrice) {
                    addProductToTable(productId, productName, quantity, unitPrice, totalPrice);
                    resetFormFields();
                } else {
                    alert('Please fill all the fields.');
                }
            });

            productList.addEventListener('input', function(event) {
                if (event.target.classList.contains('quantity-input')) {
                    const row = event.target.closest('tr');
                    const quantity = parseFloat(event.target.value) || 0;
                    const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
                    const totalPrice = (quantity * unitPrice).toFixed(2);

                    row.querySelector('.total-price').textContent = totalPrice;
                    row.querySelector('.total-price-input').value = totalPrice;

                    calculateTotalAmount();
                }
            });

            productList.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove_product')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    calculateTotalAmount();
                }
            });

            discountPercentageInput.addEventListener('input', calculateTotalPayable);
            discountAmountInput.addEventListener('input', calculateTotalPayable);
            totalPaidAmountInput.addEventListener('input', calculateTotalDueAmount);

            initializeForm();

            function initializeForm() {
                resetFormFields();
                shopSelect.dispatchEvent(new Event('change'));
            }

            // Payment type fields toggle
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

            // Fetch customer name by phone
            $('#phone').blur(function() {
                const phone = $(this).val();
                if (phone) {
                    $.ajax({
                        url: '{{ route('get-customer-name') }}',
                        type: 'GET',
                        data: {
                            phone: phone
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.name) {
                                $('#name').val(response.name);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });
        });
    </script>

    <script>
        document.getElementById('add-transaction').addEventListener('click', function() {
            var container = document.getElementById('transactions-container');
            var newTransaction = document.querySelector('.transaction-form').cloneNode(true);
            newTransaction.querySelector('.remove-transaction').addEventListener('click', function() {
                this.closest('.transaction-form').remove();
            });
            container.appendChild(newTransaction);
        });

        document.querySelectorAll('.remove-transaction').forEach(function(button) {
            button.addEventListener('click', function() {
                this.closest('.transaction-form').remove();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#create_form').submit(function() {
                if ($('#product_list tr').length === 0) {
                    alert('Please add product to the list.');
                    return false;
                } else if ($('#phone').val() === '' || $('#order_date').val() === '' || $('#order_status')
                    .val() === '') {
                    alert('Please fill all the required fields.');
                    return false;
                }
            });
        });
    </script>
@endpush

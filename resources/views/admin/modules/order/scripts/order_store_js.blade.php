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
            const totalPayableAmountInput = document.getElementById('total_payable_amount');
            const totalPaidAmountInput = document.getElementById('total_paid_amount');
            const totalDueAmountInput = document.getElementById('total_due_amount');
            const discountPercentageInput = document.getElementById('discount_percentage');
            const discountAmountInput = document.getElementById('discount_amount');
            const totalAmountInput = document.getElementById('total_amount');

            const productPrices = @json($products->pluck('price', 'id'));

            // Update product list based on shop selection
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

            // Update unit price and total price on product selection
            productSelect.addEventListener('change', function() {
                const selectedProductId = this.value;
                const unitPrice = productPrices[selectedProductId] || 0;
                unitPriceInput.value = unitPrice;
                updateTotalPrice();
            });

            // Calculate total price on quantity input change
            quantityInput.addEventListener('input', updateTotalPrice);

            // Add product to list
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

            function calculateTotalAmount() {
                let totalAmount = 0;
                const rows = productList.querySelectorAll('tr');
                rows.forEach(row => {
                    const totalPrice = parseFloat(row.querySelector('.total-price-input').value) || 0;
                    totalAmount += totalPrice;
                });
                totalAmountInput.value = totalAmount.toFixed(2);
                calculateTotalPayable();
            }

            // Calculate total payable amount after discount
            function calculateTotalPayable() {
                let totalAmount = parseFloat(totalAmountInput.value) || 0;
                let discountPercentage = parseFloat(discountPercentageInput.value) || 0;
                let discountAmount = parseFloat(discountAmountInput.value) || 0;

                if (discountPercentage > 0) {
                    discountAmount = (totalAmount * discountPercentage / 100);
                    discountAmountInput.value = discountAmount.toFixed(2);
                    totalPayableAmountInput.value = (totalAmount - discountAmount).toFixed(2);
                } else if (discountAmount > 0) {
                    totalPayableAmountInput.value = (totalAmount - discountAmount).toFixed(2);
                } else {
                    totalPayableAmountInput.value = totalAmount.toFixed(2);
                }

                calculateDueAmount();
            }

            // Calculate due amount
            function calculateDueAmount() {
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
                <td>${quantity}<input type="hidden" name="quantity[]" value="${quantity}"></td>
                <td>${unitPrice}<input type="hidden" name="unit_price[]" value="${unitPrice}"></td>
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

            productList.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('remove_product')) {
                    const row = e.target.closest('tr');
                    row.remove();
                    calculateTotalAmount();
                }
            });

            discountPercentageInput.addEventListener('input', calculateTotalPayable);
            discountAmountInput.addEventListener('input', calculateTotalPayable);
            totalPaidAmountInput.addEventListener('input', calculateDueAmount);

            initializeForm();

            function initializeForm() {
                resetFormFields();
                shopSelect.dispatchEvent(new Event('change'));
            }

            function resetFormFields() {
                productSelect.value = '';
                quantityInput.value = '';
                unitPriceInput.value = '';
                totalPriceInput.value = '';
            }
        });
    </script>




    <script>
        $(document).ready(function() {
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
        $(document).ready(function() {
            $('#create_form').submit(function() {
                if ($('#product_list tr').length === 0) {
                    alert('Please add product to the list.');
                    return false;
                }
                elseif($('#phone').val() === '' || $('#order_date').val() === '' || $('#order_status').val() === '') {
                    alert('Please fill all the required fields.');
                    return false;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectedProductId = '{{ $productId }}';
            if (selectedProductId) {
                const productSelect = document.getElementById('product_id');
                if (productSelect) {
                    productSelect.value = selectedProductId;
                    const event = new Event('change');
                    productSelect.dispatchEvent(event);
                }
            }
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
    </script>
@endpush

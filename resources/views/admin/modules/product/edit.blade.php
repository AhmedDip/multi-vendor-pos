@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{ html()->modelForm($product, 'PUT', route('product.update', $product->id))->id('create_form')->open() }}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.product.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'edit'" />
                </div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        function filterOptionsByShop(selectedShopId) {
            $('.attribute-select').each(function() {
                var selectedAttribute = $(this).val();
                $(this).find('option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId || $(this).val() == selectedAttribute) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#category_id').each(function() {
                var selectedCategory = $(this).val();
                $(this).find('option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId || $(this).val() == selectedCategory) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#brand_id').each(function() {
                var selectedBrand = $(this).val();
                $(this).find('option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId || $(this).val() == selectedBrand) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('#warehouse_id').each(function() {
                var selectedWarehouse = $(this).val();
                $(this).find('option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId || $(this).val() == selectedWarehouse) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }); 

   
            $('.attribute-select').each(function() {
                var selectedAttributeId = $(this).val();
                var $valueSelect = $(this).closest('.attribute-row').find('.attribute-value-select');
                $valueSelect.find('option').each(function() {
                    var attributeId = $(this).data('attribute-id');
                    if (selectedAttributeId == attributeId || $(this).val() == $valueSelect.val()) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        }

        var initialShopId = $('#shop_id').val();
        if (initialShopId) {
            filterOptionsByShop(initialShopId);
        }

        $('#shop_id').change(function() {
            var selectedShopId = $(this).val();
            filterOptionsByShop(selectedShopId);
        });

        $(document).on('change', '.attribute-select', function() {
            var selectedAttributeId = $(this).val();
            var $valueSelect = $(this).closest('.attribute-row').find('.attribute-value-select');
            $valueSelect.val('');
            $valueSelect.find('option').each(function() {
                var attributeId = $(this).data('attribute-id');
                if (selectedAttributeId == attributeId) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $(document).on('click', '.add-attribute-row', function() {
            var $attributeRow = $('#attributes-wrapper .attribute-row').first().clone();
            $attributeRow.find('.attribute-select').val('');
            $attributeRow.find('.attribute-value-select').val('').find('option').hide();
            $('#attributes-wrapper').append($attributeRow);
        });

        $(document).on('click', '.remove-attribute-row', function() {
            if ($('#attributes-wrapper .attribute-row').length > 1) {
                $(this).closest('.attribute-row').remove();
            } else {
                alert('At least one attribute is required.');
            }
        });
    });
</script>


<script>
    $(document).ready(function() {
        var $typeSelect = $('#type');
        var $additionalFields = $('#additional-fields');
        var $durationWrapper = $('#duration-wrapper');
        var $slotWrapper = $('#slot-wrapper');
        var $expiryDateWrapper = $('#expiry-date-wrapper');

        function toggleFields() {
            var selectedType = $typeSelect.val();

            if (selectedType == '1') {
                $additionalFields.show();
                $durationWrapper.hide();
                $slotWrapper.hide();
                $expiryDateWrapper.show();
            } else if (selectedType == '2') {
                $additionalFields.show();
                $durationWrapper.show();
                $slotWrapper.show();
                $expiryDateWrapper.hide();
            } else {
                $additionalFields.hide();
            }
        }

        $typeSelect.change(toggleFields);
        toggleFields(); // Call it initially to set the correct fields
    });
</script>
@endpush

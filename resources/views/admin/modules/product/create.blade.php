@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{ html()->form('post', route('product.store'))->id('create_form')->open() }}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.product.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'create'" />
                </div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.attribute-value-select option').hide();
            $('.attribute-select option').hide();
            $('#category_id option').hide();
            $('#brand_id option').hide();

            $('#shop_id').change(function() {
                var selectedShopId = $(this).val();
                // console.log(selectedShopId);

                $('.attribute-select option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('.attribute-select').val('');
                $('.attribute-value-select').val('');

                $('#category_id option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#category_id').val('');

                $('#brand_id option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#brand_id').val('');

                $('#warehouse_id option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#warehouse_id').val('');

                $('#manufacturer_id option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#manufacturer_id').val('');
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
            var $productWrapper = $('.product-wrapper');

            function toggleFields() {
                var selectedType = $typeSelect.val();

                if (selectedType == '1') {
                    $additionalFields.show();
                    $durationWrapper.hide();
                    $slotWrapper.hide();
                    $expiryDateWrapper.show();
                    $productWrapper.show();
                } else if (selectedType == '2') {
                    $additionalFields.show();
                    $durationWrapper.show();
                    $slotWrapper.show();
                    $expiryDateWrapper.hide();
                    $productWrapper.hide();
                } else {
                    $additionalFields.hide();
                }
            }

            $typeSelect.change(toggleFields);
            toggleFields(); // Call it initially to set the correct fields
        });
    </script>
@endpush

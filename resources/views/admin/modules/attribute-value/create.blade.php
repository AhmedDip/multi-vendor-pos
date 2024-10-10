@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{html()->form('post',route('attribute-value.store'))->id('create_form')->open()}}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.attribute-value.partials.form')
                <div class="row justify-content-center">
                    <div class="col-2">
                        <x-submit-button :type="'create'"/>
                    </div>
                </div>
            </div>
            {{html()->form()->close()}}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#shop_id').change(function() {
                var selectedShopId = $(this).val();

                $('#attribute_id option').each(function() {
                    var shopId = $(this).data('shop-id');
                    if (selectedShopId == shopId) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
                $('#attribute_id').val('');
            });
        });
    </script>

  
@endpush
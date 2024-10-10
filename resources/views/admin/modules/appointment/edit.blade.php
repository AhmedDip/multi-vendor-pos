@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            {{html()->modelForm($appointment, 'PUT', route('appointment.update',  $appointment->id))->id('create_form')->open()}}

            <div class="row justify-content-center align-items-end">
                @include('admin.modules.appointment.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>

            {{html()->form()->close()}}
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#shop_id').change(function(){
                var selectedShopId = $(this).val();
                $('#category_id option').each(function(){
                    var shopId = $(this).data('shop-id');
                    if(selectedShopId == shopId){
                        $(this).show();
                    }else{
                        $(this).hide();
                    }
                });
                $('#category_id').val('');
                
            });
        });
        
        // $(document).ready(function(){
        //     $('#category_id').change(function(){
        //         var selectedCategoryId = $(this).val();
        //         $('#product_id option').each(function(){
        //             var categoryId = $(this).data('category_id');
        //             if(selectedCategoryId == categoryId){
        //                 $(this).show();
        //             }else{
        //                 $(this).hide();
        //             }
        //         });
        //         $('#product_id').val('');
                
        //     });
        // });
        
       
        $(document).ready(function() {
        $('.select2').select2();
    });
    </script>
    
    {{-- ------------------- --}}
    {{--------- for multiple service selection ---------}}
    {{-- ------------------- --}}

    <script>
        $(document).ready(function() {
            function fetchServices(categoryId, selectedServices = []) {
                const serviceSelect = $('#product_id');
                serviceSelect.empty(); // Clear existing options
                

                if (!categoryId) {
                    return; // No category selected
                }

                // Fetch services based on the selected category
                const services = @json($services_type);

                if (services[categoryId]) {
                    services[categoryId].forEach(service => {
                        const option = new Option(service.name, service.id, false, selectedServices.includes(service.id));
                        serviceSelect.append(option);
                    });
                }
            }

            // Initialize service select options based on the initial category selection
            const initialCategoryId = $('#category_id').val();
            const selectedServices = @json($selectedServices);
            fetchServices(initialCategoryId, selectedServices);

            // Event listener for shop change to filter categories
            $('#shop_id').change(function() {
                const selectedShopId = $(this).val();

                // Filter categories based on selected shop
                $('#category_id option').each(function() {
                    const shopId = $(this).data('shop-id');
                    $(this).toggle(selectedShopId == shopId);
                });
                $('#category_id').val('').trigger('change');
            });

            // Event listener for category change to fetch services
            $('#category_id').change(function() {
                const selectedCategoryId = $(this).val();
                fetchServices(selectedCategoryId);
            });

            // Initialize Select2
            $('.select2').select2();
        });
    </script>

    {{-- ------------------- --}}
        {{-- ********** for multiple service selection End ****** --}}
    {{-- ------------------- --}}
@endpush


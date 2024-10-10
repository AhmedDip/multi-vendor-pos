@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            {{html()->form('post',route('appointment.store'))->id('create_form')->open()}}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.appointment.partials.form')
                <div class="col-md-2">
                    <x-submit-button :type="'create'"/>
                </div>
            </div>
            {{html()->form()->close()}}
        </div>
    </div>

@endsection

@push('scripts')
<script>
    
    document.addEventListener('DOMContentLoaded', function(){
        const shopSelect   = document.getElementById('shop_id');
        const categoryType = document.getElementById('category_id');
        
        shopSelect.addEventListener('change', function(){
            const selectedShopId = this.value;
            
            Array.from(categoryType.options).forEach(option => {
                if (!selectedShopId || option.dataset.shopId == selectedShopId){
                    option.style.display = 'block';
                }else{
                    option.style.display = 'none';
                }
            });
            
            categorySelect.value = '';
        });
        shopSelect.dispatchEvent(new Event('change'));
    });
    
    
    $document.ready(function(){
        $('.select2').select2();
    });
</script>




<script>
    function fetchServices(categoryId) {
        const serviceSelect = document.getElementById('product_id');
        serviceSelect.innerHTML = ''; //clear existing data
        if(!categoryId) {
            return; //no category selected
        }
        
        
        //fetch data
        const services = @json($services_type);
        if (services[categoryId]) {
            services[categoryId].forEach(service => {
                const option = document.createElement('option');
                option.value = service.id;
                option.textContent = service.name;
                serviceSelect.appendChild(option);
            });
        }
        
        validationProductSelection();
        
    }
    function validationProductSelection(){
        const selectedProducts = Array.form(document.getElementById('product_id').selectedOptions);
        const errorElement = document.getElementById('product_id_error');
        
        if(selectedProducts.length ===0){
            errorElement.textContent = "Please select at least one service";
            return false;
        }else{
            errorElement.textContent = '';
            return true;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function (){
        const initialCategoryId = document.getElementById('category_id').value;
        fetchServices(initialCategoryId);
    });
    document.getElementById('category_id').addEventListener('change', function () {
        const selectedCategoryId = this.value;
        fetchServices(selectedCategoryId);
    });
    
    document.getElementById('create_form').addEventListener('submit', function (event) {
        if(!validationProductSelection()){
            event.preventDefault();
        }
    });
      
    
</script>

{{-- ------------------- --}}
    {{-- ********** for multiple service selection End ****** --}}
{{-- ------------------- --}}

@endpush

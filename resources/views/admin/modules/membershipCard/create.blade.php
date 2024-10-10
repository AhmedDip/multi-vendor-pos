



{{-- ---------------------- --}}

@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            {{html()->form('post',route('membership-card.store'))->id('create_form')->open()}}
            <div class="row justify-content-center align-items-end">
                @include('admin.modules.membershipCard.partials.form')
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
        document.addEventListener('DOMContentLoaded', function() {
            const shopSelect = document.getElementById('shop_id');
            const membershipCardType = document.getElementById('membership_card_type_id');

            shopSelect.addEventListener('change', function() {
                const selectedShopId = this.value;

                Array.from(membershipCardType.options).forEach(option => {
                    if (!selectedShopId || option.dataset.shopId == selectedShopId) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });

                categorySelect.value = '';
            });

            shopSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endpush
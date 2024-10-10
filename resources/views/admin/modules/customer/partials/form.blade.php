<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Customer Name', 'name')}}
        <x-required/>
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Customer name'))}}
        <x-validation-error :errors="$errors->first('name')"/>
    </div>
</div>

<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Address', 'address')}}
        <x-required/>
        {{html()->text('address')->class('form-control form-control-sm '. ($errors->has('address') ? 'is-invalid' : ''))->placeholder(__('Enter Your Address'))}}
        <x-validation-error :errors="$errors->first('address')"/>
    </div>
</div>
<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Contact Number', 'phone')}}
        <x-required/>
        {{html()->text('phone')->class('form-control form-control-sm '. ($errors->has('phone') ? 'is-invalid' : ''))->placeholder(__('Enter Contact Number'))}}
        <x-validation-error :errors="$errors->first('phone')"/>
    </div>
</div>
<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Shop', 'shop_id')}}
        <x-required/>
        {{html()->select('shop_id',$shop)->class('form-select form-select-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder(__('Select Shop'))}}
        <x-validation-error :errors="$errors->first('shop_id')"/>
    </div>
    
</div>
<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Membership Card No', 'membership_card_id')}}
        <x-required/>
        <select id="membership_card_id" name="membership_card_id" class="form-select form-select-sm {{ $errors->has('membership_card_id') ? 'is-invalid' : '' }}">
            <option value="">{{ __('Select Membership Card No') }}</option>
            @foreach($membership_card_no as $shopId => $membershipCardNos)
                @foreach($membershipCardNos as $membershipCardNo)
                    <option value="{{ $membershipCardNo->id }}" data-shop-id="{{ $shopId }}" {{ isset($customer) && $customer->membership_card_id == $membershipCardNo->id ? 'selected' : '' }}>{{ $membershipCardNo->card_no }}</option>
                @endforeach
            @endforeach
        </select>
       
        <x-validation-error :errors="$errors->first('membership_card_id')"/>
    </div>
</div>


<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required/>
        {{html()->select('status',\App\Models\Customer::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Collection Status'))}}
        <x-validation-error :errors="$errors->first('status')"/>
    </div>
</div>


@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const shopSelect = document.getElementById('shop_id');
        const membershipCardNo = document.getElementById('membership_card_id');

        shopSelect.addEventListener('change', function () {
            const shopId = this.value;
            const membershipCardNoOptions = membershipCardNo.querySelectorAll('option');

            membershipCardNoOptions.forEach(option => {
                if (option.dataset.shopId === shopId) {
                    option.hidden = false;
                } else {
                    option.hidden = true;
                }
            });
        });

    });
    </script>
    

@endpush







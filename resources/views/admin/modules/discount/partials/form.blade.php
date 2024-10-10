<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
        {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Discount Name', 'name')}}
        <x-required/>
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Discounts name'))}}
        <x-validation-error :errors="$errors->first('name')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Discount slug', 'slug')}}
        <x-required/>
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Discounts slug'))}}
        <x-validation-error :errors="$errors->first('slug')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Discount amount', 'amount')}}
        <x-required/>
        {{html()->number('amount')->class('form-control form-control-sm '. ($errors->has('amount') ? 'is-invalid' : ''))->placeholder(__('Enter Discounts amount'))}}
        <x-validation-error :errors="$errors->first('amount')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Discount percentage', 'percentage')}}
        <x-required/>
        {{html()->number('percentage')->class('form-control form-control-sm '. ($errors->has('percentage') ? 'is-invalid' : ''))->placeholder(__('Enter Discounts percentage'))}}
        <x-validation-error :errors="$errors->first('percentage')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Coupon Code', 'coupon_code')}}
        <x-required/>
        {{html()->text('coupon_code')->class('form-control form-control-sm '. ($errors->has('coupon_code') ? 'is-invalid' : ''))->placeholder(__('Enter coupon code'))}}
        <x-validation-error :errors="$errors->first('coupon_code')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Discount sort order', 'sort_order')}}
        <x-required/>
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter Discounts sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')"/>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required/>
        {{html()->select('status',\App\Models\Discount::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Discounts Status'))}}
        <x-validation-error :errors="$errors->first('status')"/>
    </div>
</div>
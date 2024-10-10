<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
        {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Warehouse Name', 'name')}}
        <x-required />
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Warehouse name'))}}
        <x-validation-error :errors="$errors->first('name')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Warehouse slug', 'slug')}}
        <x-required />
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Warehouse slug'))}}
        <x-validation-error :errors="$errors->first('slug')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Warehouse phone', 'phone')}}
        <x-required />
        {{html()->number('phone')->class('form-control form-control-sm '. ($errors->has('phone') ? 'is-invalid' : ''))->placeholder(__('Enter Warehouse phone'))}}
        <x-validation-error :errors="$errors->first('phone')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Warehouse street address', 'street_address')}}
        <x-required />
        {{html()->text('street_address')->class('form-control form-control-sm '. ($errors->has('street_address') ? 'is-invalid' : ''))->placeholder(__('Enter Warehouse street address'))}}
        <x-validation-error :errors="$errors->first('street_address')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Warehouse sort order', 'sort_order')}}
        <x-required />
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter Warehouse sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')" />
    </div>
</div>

<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required />
        {{html()->select('status',\App\Models\Warehouse::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Warehouse Status'))}}
        <x-validation-error :errors="$errors->first('status')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
        {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
    <i class="fas fa-tag"></i>
        {{html()->label('Payment Method Name', 'name')}}
        <x-required />
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Payment Method name'))}}
        <x-validation-error :errors="$errors->first('name')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
    <i class="fas fa-link"></i>
        {{html()->label('Payment Method slug', 'slug')}}
        <x-required />
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Payment Method slug'))}}
        <x-validation-error :errors="$errors->first('slug')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
    <i class="fas fa-sort-numeric-down"></i>
        {{html()->label('Sort order', 'sort_order')}}
        <x-required />
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
    <i class="fas fa-toggle-on"></i>
        {{html()->label('Status', 'status')}}
        <x-required />
        {{html()->select('status',\App\Models\PaymentMethod::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Status'))}}
        <x-validation-error :errors="$errors->first('status')" />
    </div>
</div>
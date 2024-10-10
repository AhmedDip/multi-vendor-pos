<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Shop', 'shop_id')}}
        <x-required/>
        {{html()->text('shop_id')->class('form-control form-control-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder(__('Enter Shop'))}}
        <x-validation-error :errors="$errors->first('shop_id')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Shipping method Name', 'name')}}
        <x-required/>
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Shipping method name'))}}
        <x-validation-error :errors="$errors->first('name')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Shipping method slug', 'slug')}}
        <x-required/>
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Shipping method slug'))}}
        <x-validation-error :errors="$errors->first('slug')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Sort order', 'sort_order')}}
        <x-required/>
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter Brand sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required/>
        {{html()->select('status',\App\Models\ShippingMethod::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Brand Status'))}}
        <x-validation-error :errors="$errors->first('status')"/>
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Shop', 'shop_id')}}
        <x-required/>
        {{html()->select('shop_id', $shops)->class('form-select form-select-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder(__('Select Shop'))}}
        <x-validation-error :errors="$errors->first('shop_id')"/>
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Attribute Name', 'name')}}
        <x-required/>
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes name'))}}
        <x-validation-error :errors="$errors->first('name')"/>
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Attribute slug', 'slug')}}
        <x-required/>
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes slug'))}}
        <x-validation-error :errors="$errors->first('slug')"/>
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Attribute sort order', 'sort_order')}}
        <x-required/>
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')"/>
    </div>
</div>

<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required/>
        {{html()->select('status',\App\Models\Attribute::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Attributes Status'))}}
        <x-validation-error :errors="$errors->first('status')"/>
    </div>
</div>


<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Description', 'description')}}
        <x-required/>
        {{html()->textarea('description')->class('form-select form-select-sm '. ($errors->has('description') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes description'))}}
        <x-validation-error :errors="$errors->first('description')"/>
    </div>
</div>
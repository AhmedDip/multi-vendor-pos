<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Shop', 'shop_id')}}
        <x-required/>
        {{html()->select('shop_id',$shop)->class('form-select form-select-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder(__('Select Shop'))}}
        <x-validation-error :errors="$errors->first('shop_id')"/>
    </div>
</div>
<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Purpose', 'purpose')}}
        <x-required/>
        {{html()->text('purpose')->class('form-control form-control-sm '. ($errors->has('purpose') ? 'is-invalid' : ''))->placeholder(__('Enter Purpose'))}}
        <x-validation-error :errors="$errors->first('purpose')"/>
    </div>
</div>
<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Amount', 'amount')}}
        <x-required/>
        {{html()->text('amount')->class('form-control form-control-sm '. ($errors->has('amount') ? 'is-invalid' : ''))->placeholder(__('Enter Amount'))}}
        <x-validation-error :errors="$errors->first('amount')"/>
    </div>
</div>

<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Date', 'date')}}
        <x-required/>
        {{html()->datetime('date')->class('form-control form-control-sm '. ($errors->has('date') ? 'is-invalid' : ''))->placeholder(__('Enter Date'))}}
        <x-validation-error :errors="$errors->first('date')"/>
    </div>
</div>
{{-- <div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Shop Id', 'shop_id')}}
        <x-required/>
        {{html()->number('shop_id')->class('form-control form-control-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder(__('Enter Shop Id'))}}
        <x-validation-error :errors="$errors->first('shop_id')"/>
    </div>
</div> --}}









<div class="row">
    <div class="mb-4 col-md-3">
        <div class="custom-form-group">
            {{html()->label('Membership Card Type', 'card_type_name')}}
            <x-required/>
            {{html()->text('card_type_name')->class('form-control form-control-sm '. ($errors->has('card_type_name') ? 'is-invalid' : ''))->placeholder(__('Enter Membership Card Type'))}}
            <x-validation-error :errors="$errors->first('card_type_name')"/>
        </div>
    </div>
    <div class="mb-4 col-md-3">
        <div class="custom-form-group">
            {{html()->label('Discount Percentage (%)', 'discount')}}
            <x-required/>
            {{html()->text('discount')->class('form-control form-control-sm '. ($errors->has('discount') ? 'is-invalid' : ''))->placeholder(__('Enter Discount'))}}
            <x-validation-error :errors="$errors->first('discount')"/>
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
            {{html()->label('Status', 'status')}}
            <x-required/>
            {{html()->select('status',\App\Models\MembershipCardType::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Collection Status'))}}
            <x-validation-error :errors="$errors->first('status')"/>
        </div>
    </div>
    
</div>





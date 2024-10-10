<div class="row">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Shop Name', 'name')}}
            <x-required/>
            {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Shop name'))}}
            <x-validation-error :errors="$errors->first('name')"/>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Shop slug', 'slug')}}
            <x-required/>
            {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Shop slug'))}}
            <x-validation-error :errors="$errors->first('slug')"/>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Phone', 'phone')}}
            <x-required/>
            {{html()->text('phone')->class('form-control form-control-sm '. ($errors->has('phone') ? 'is-invalid' : ''))->placeholder(__('Enter Shop phone'))}}
            <x-validation-error :errors="$errors->first('phone')"/>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Email', 'email')}}
            <x-required/>
            {{html()->email('email')->class('form-control form-control-sm '. ($errors->has('email') ? 'is-invalid' : ''))->placeholder(__('Enter Shop email'))}}
            <x-validation-error :errors="$errors->first('email')"/>
        </div>
    </div>
    
    
    <div class="col-md-12 mb-4">
        <div class="custom-form-group">
            {{html()->label('Description', 'description')}}
            <x-required/>
            {{html()->textarea('description')->id('editor')->class('form-select form-select-sm '. ($errors->has('description') ? 'is-invalid' : ''))->placeholder(__('Enter Collection description'))}}
            <x-validation-error :errors="$errors->first('description')"/>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Address', 'address')}}
            <x-required/>
            {{html()->text('address')->class('form-control form-control-sm '. ($errors->has('address') ? 'is-invalid' : ''))->placeholder(__('Enter Shop address'))}}
            <x-validation-error :errors="$errors->first('address')"/>
        </div>
    </div>
    
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Shop Owner', 'shop_owner_id')}}
            <x-required/>
            {{html()->select('shop_owner_id',$shop_owners)->class('form-select select2 form-select-sm '. ($errors->has('shop_owner_id') ? 'is-invalid' : ''))->placeholder(__('Select Shop Owner'))}}
            <x-validation-error :errors="$errors->first('shop_owner_id')"/>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Status', 'status')}}
            <x-required/>
            {{html()->select('status',\App\Models\Shop::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Shop Status'))}}
            <x-validation-error :errors="$errors->first('status')"/>
        </div>
    </div>
    
    
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Color', 'shop_color')}}
            <x-required/>
           <input type="color" name="shop_color" value="{{old('shop_color') ?? $shop?->shop_color ?? '#000000'}}" class="form-control form-control-sm">
            <x-validation-error :errors="$errors->first('color')"/>
        </div>
    </div>
    
    
    <div class="row justify-content-center my-4">
        <div class="col-md-4">
            {{html()->label('Photo')->class('text-start')}}
            <x-media-library
                :inputname="'photo'"
                :multiple="false"
                :displaycolumn="12"
                :uniqueid="1"
            />
            @isset($shop->photo->photo)
                <div class="text-center">
                    <img src="{{get_image($shop->photo->photo)}}" alt="image" class="img-thumbnail m-auto">
                </div>
            @endisset
        </div>
    </div>
    
</div>
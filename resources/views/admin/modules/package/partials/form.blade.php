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
        {{html()->label('Plan', 'plan')}}
        <x-required/>
        {{html()->text('plan')->class('form-control form-control-sm '. ($errors->has('plan') ? 'is-invalid' : ''))->placeholder(__('Enter plan'))}}
        <x-validation-error :errors="$errors->first('plan')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Tagline', 'tagline')}}
        <x-required/>
        {{html()->text('tagline')->class('form-control form-control-sm '. ($errors->has('tagline') ? 'is-invalid' : ''))->placeholder(__('Enter tagline'))}}
        <x-validation-error :errors="$errors->first('tagline')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Quota', 'quota')}}
        <x-required/>
        {{html()->text('quota')->class('form-control form-control-sm '. ($errors->has('quota') ? 'is-invalid' : ''))->placeholder(__('Enter quota'))}}
        <x-validation-error :errors="$errors->first('quota')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Price', 'price')}}
        <x-required/>
        {{html()->number('price')->class('form-control form-control-sm '. ($errors->has('price') ? 'is-invalid' : ''))->attribute('step', '0.01')->placeholder(__('Enter price'))}}
        <x-validation-error :errors="$errors->first('price')"/>
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Sort order', 'sort_order')}}
        <x-required/>
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')"/>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required/>
        {{html()->select('status',\App\Models\Package::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Status'))}}
        <x-validation-error :errors="$errors->first('status')"/>
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
        @isset($package->photo->photo)
            <div class="text-center">
                <img src="{{get_image($package->photo->photo)}}" alt="image" class="img-thumbnail m-auto">
            </div>

        @endisset
    </div>
</div>





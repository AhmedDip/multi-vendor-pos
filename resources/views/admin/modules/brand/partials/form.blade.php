<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
        {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Brand Name', 'name')}}
        <x-required />
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Brand name'))}}
        <x-validation-error :errors="$errors->first('name')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Brand slug', 'slug')}}
        <x-required />
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Brand slug'))}}
        <x-validation-error :errors="$errors->first('slug')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Brand sort order', 'sort_order')}}
        <x-required />
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter Brand sort order'))}}
        
        <x-validation-error :errors="$errors->first('sort_order')" />
    </div>
</div>

<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required />
        {{html()->select('status',\App\Models\Brand::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Brand Status'))}}
        <x-validation-error :errors="$errors->first('status')" />
    </div>
</div>


<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Description', 'description')}}
        <x-required />
        {{html()->textarea('description')->class('form-select form-select-sm '. ($errors->has('description') ? 'is-invalid' : ''))->placeholder(__('Enter Brand description'))}}
        <x-validation-error :errors="$errors->first('description')" />
    </div>
</div>



<div class="my-4 row justify-content-center">
    <div class="col-md-4">
        {{html()->label('Photo')->class('text-start')}}
        <x-media-library :inputname="'photo'" :multiple="false" :displaycolumn="12" :uniqueid="1" />
        @isset($brand->photo->photo)
        <div class="text-center">
            <img src="{{get_image($brand->photo->photo)}}" alt="image" class="m-auto img-thumbnail">
        </div>

        @endisset
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
        {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Manufacturer Name', 'name')}}
        <x-required />
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter manufacturer name'))}}
        <x-validation-error :errors="$errors->first('name')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Manufacturer slug', 'slug')}}
        <x-required />
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter manufacturer slug'))}}
        <x-validation-error :errors="$errors->first('slug')" />
    </div>
</div>
<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Manufacturer sort order', 'sort_order')}}
        <x-required />
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter manufacturer sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')" />
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required />
        {{html()->select('status',\App\Models\Manufacturer::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select manufacturer Status'))}}
        <x-validation-error :errors="$errors->first('status')" />
    </div>
</div>
<div class="row justify-content-center my-4">
    <div class="col-md-4">
        {{html()->label('Photo')->class('text-start')}}
        <x-media-library :inputname="'photo'" :multiple="false" :displaycolumn="12" :uniqueid="1" />
        @isset($manufacturer->photo->photo)
        <div class="text-center">
            <img src="{{get_image($manufacturer->photo->photo)}}" alt="image" class="img-thumbnail m-auto">
        </div>

        @endisset
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Shop', 'shop_id')}}
        <x-required />
        {{html()->select('shop_id', $shops)->class('form-select form-select-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder(__('Select Shop'))}}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>
</div>
<div class="mb-4 col-md-6">
        <div class="custom-form-group">
            <label for="attribute_id"><i class="fas fa-list"></i> {{ __('Attribute') }}</label>
            <select id="attribute_id" name="attribute_id"
                class="form-select form-select-sm {{ $errors->has('attribute_id') ? 'is-invalid' : '' }}">
                <option value="">{{ __('Select Attribute') }}</option>
                {{-- @foreach ($attributes as $shopId => $shopAttributes)
                    @if (is_array($shopAttributes))
                        @foreach ($shopAttributes as $attribute) --}}
                            {{-- @if (is_OBJECT($attribute))
                             <option value="{{ $attribute->id }}" data-shop-id="{{ $shopId }}">
                                {{ $attribute->name }}</option>
                            @endif --}}

                            @foreach ($attributes as $shopId => $shopAttributes)
                            @foreach ($shopAttributes as $attribute)
                                <option value="{{ $attribute->id }}" data-shop-id="{{ $shopId }}">
                                    {{ $attribute->name }}</option>
                            @endforeach
                        @endforeach
                            

                        {{-- @endforeach
                    
                    @endif
                @endforeach --}}
            </select>
            <x-validation-error :errors="$errors->first('attribute_id')" />
        </div>
    </div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Attribute value Name', 'name')}}
        <x-required />
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes name'))}}
        <x-validation-error :errors="$errors->first('name')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Attribute value slug', 'slug')}}
        <x-required />
        {{html()->text('slug')->class('form-control form-control-sm '. ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes slug'))}}
        <x-validation-error :errors="$errors->first('slug')" />
    </div>
</div>
<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Attribute value sort order', 'sort_order')}}
        <x-required />
        {{html()->text('sort_order')->class('form-control form-control-sm '. ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes sort order'))}}
        <x-validation-error :errors="$errors->first('sort_order')" />
    </div>
</div>

<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Status', 'status')}}
        <x-required />
        {{html()->select('status',\App\Models\Attribute::STATUS_LIST)->class('form-select form-select-sm '. ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Attributes Status'))}}
        <x-validation-error :errors="$errors->first('status')" />
    </div>
</div>


<div class="mb-4 col-md-6">
    <div class="custom-form-group">
        {{html()->label('Description', 'description')}}
        <x-required />
        {{html()->textarea('description')->class('form-select form-select-sm '. ($errors->has('description') ? 'is-invalid' : ''))->placeholder(__('Enter Attributes description'))}}
        <x-validation-error :errors="$errors->first('description')" />
    </div>
</div>


@push('scripts')

<script>

</script>

@endpush
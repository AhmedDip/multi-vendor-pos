<div class="row">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="shop_id"><i class="fas fa-store"></i> {{ __('Shop') }}</label>
            {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
            <x-validation-error :errors="$errors->first('shop_id')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="type"><i class="fas fa-toggle-on"></i> {{ __('Type') }}</label>
            {{ html()->select('type', \App\Models\Product::TYPE_LIST)->class('form-select form-select-sm ' . ($errors->has('type') ? 'is-invalid' : ''))->placeholder('Select Type')->id('type') }}
            <x-validation-error :errors="$errors->first('type')" />
        </div>
    </div>

</div>

    <div class="row" id="additional-fields" style="display: none;">
        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="name"><i class="fas fa-tag"></i> {{ __('Product Name') }}</label>
                {{ html()->text('name')->class('form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''))->placeholder('Enter Product Name') }}
                <x-validation-error :errors="$errors->first('name')" />
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="slug"><i class="fas fa-link"></i> {{ __('Product Slug') }}</label>
                {{ html()->text('slug')->class('form-control form-control-sm ' . ($errors->has('slug') ? 'is-invalid' : ''))->placeholder('Enter Product Slug') }}
                <x-validation-error :errors="$errors->first('slug')" />
            </div>
        </div>

        <div class="col-md-6 mb-4" id="duration-wrapper">
            <div class="custom-form-group">
                <label for="duration"><i class="fas fa-clock"></i> {{ __('Duration (Minutes)') }}</label>
                {{ html()->text('duration')->class('form-control form-control-sm ' . ($errors->has('duration') ? 'is-invalid' : ''))->placeholder('Enter duration') }}
                <x-validation-error :errors="$errors->first('duration')" />
            </div>
        </div>

        <div class="col-md-6 mb-4" id="slot-wrapper">
            <div class="custom-form-group">
                <label for="slot">
                    <i class="fas fa-dot-circle"></i> {{ __('Slot') }}</label>
                {{ html()->text('slot')->class('form-control form-control-sm ' . ($errors->has('slot') ? 'is-invalid' : ''))->placeholder('Enter slot') }}
                <x-validation-error :errors="$errors->first('slot')" />
            </div>
        </div>

        <div class="col-md-6 mb-4" id="expiry-date-wrapper">
            <div class="custom-form-group">
                <label for="expiry_date"><i class="fas fa-calendar"></i> {{ __('Expiry Date') }}</label>
                {{ html()->date('expiry_date')->class('form-control form-control-sm ' . ($errors->has('expiry_date') ? 'is-invalid' : ''))->placeholder('Enter expiry date') }}
                <x-validation-error :errors="$errors->first('expiry_date')" />
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="sku"><i class="fas fa-barcode"></i> {{ __('Product SKU') }}</label>
                {{ html()->text('sku')->class('form-control form-control-sm ' . ($errors->has('sku') ? 'is-invalid' : ''))->placeholder('Enter Product SKU') }}
                <x-validation-error :errors="$errors->first('sku')" />
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="price"><i class="fas fa-dollar-sign"></i> {{ __('Product Price') }}</label>
                {{ html()->text('price')->class('form-control form-control-sm ' . ($errors->has('price') ? 'is-invalid' : ''))->placeholder('Enter Product Price') }}
                <x-validation-error :errors="$errors->first('price')" />
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="cost_price"><i class="fas fa-dollar-sign"></i> {{ __('Cost Price') }}</label>
                {{ html()->text('cost_price')->class('form-control form-control-sm ' . ($errors->has('cost_price') ? 'is-invalid' : ''))->placeholder('Enter Cost Price') }}
                <x-validation-error :errors="$errors->first('cost_price')" />
            </div>
        </div>


        <div class="col-md-6 mb-4 product-wrapper">
            <div class="custom-form-group">
                <label for="stock"><i class="fas fa-boxes"></i> {{ __('Stock') }}</label>
                {{ html()->text('stock')->class('form-control form-control-sm ' . ($errors->has('stock') ? 'is-invalid' : ''))->placeholder('Enter Stock Quantity') }}
                <x-validation-error :errors="$errors->first('stock')" />
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="category_id"><i class="fas fa-list"></i> {{ __('Category') }}</label>
                <select id="category_id" name="category_id"
                    class="form-select form-select-sm {{ $errors->has('category_id') ? 'is-invalid' : '' }}">
                    <option value="">{{ __('Select Category') }}</option>
                    @foreach ($categories as $shopId => $shopCategories)
                        @foreach ($shopCategories as $category)
                            <option value="{{ $category->id }}" data-shop-id="{{ $shopId }}"
                                {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                <x-validation-error :errors="$errors->first('category_id')" />
            </div>
        </div>
        <div class="col-md-6 mb-4 product-wrapper">
            <div class="custom-form-group">
                <label for="brand_id"><i class="fas fa-tags"></i> {{ __('Brand') }}</label>
                <select id="brand_id" name="brand_id"
                    class="form-select form-select-sm {{ $errors->has('brand_id') ? 'is-invalid' : '' }}">
                    <option value="">{{ __('Select Brand') }}</option>
                    @foreach ($brands as $shopId => $shopBrands)
                        @foreach ($shopBrands as $brand)
                            <option value="{{ $brand->id }}" data-shop-id="{{ $shopId }}"
                                {{ isset($product) && $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                <x-validation-error :errors="$errors->first('brand_id')" />
            </div>
        </div>


        <div class="col-md-6 mb-4 product-wrapper">
            <div class="custom-form-group">
                <label for="warehouse_id"><i class="fas fa-warehouse"></i> {{ __('Warehouse') }}</label>
                <select id="warehouse_id" name="warehouse_id"
                    class="form-select form-select-sm {{ $errors->has('warehouse_id') ? 'is-invalid' : '' }}">
                    <option value="">{{ __('Select Warehouse') }}</option>
                    @foreach ($warehouses as $shopId => $shopWarehouses)
                        @foreach ($shopWarehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" data-shop-id="{{ $shopId }}"
                                {{ isset($product) && $product->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                                {{ $warehouse->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                <x-validation-error :errors="$errors->first('warehouse_id')" />
            </div>
        </div>

        <div class="col-md-6 mb-4 product-wrapper">
            <div class="custom-form-group">
                <label for="manufacturer_id"><i class="fas fa-industry"></i> {{ __('Manufacturer') }}</label>
                <select id="manufacturer_id" name="manufacturer_id"
                    class="form-select form-select-sm {{ $errors->has('manufacturer_id') ? 'is-invalid' : '' }}">
                    <option value="">{{ __('Select Manufacturer') }}</option>
                    @foreach ($manufacturers as $shopId => $shopManufacturers)
                        @foreach ($shopManufacturers as $manufacturer)
                            <option value="{{ $manufacturer->id }}" data-shop-id="{{ $shopId }}"
                                {{ isset($product) && $product->manufacturer_id == $manufacturer->id ? 'selected' : '' }}>
                                {{ $manufacturer->name }}</option>
                        @endforeach
                    @endforeach
                </select>
                <x-validation-error :errors="$errors->first('manufacturer_id')" />
            </div>
        </div>

        
        <div class="col-md-6 mb-4 product-wrapper">
            <div class="custom-form-group">
                <label for="shelf_location"><i class="fas fa-map marker"></i> {{ __('Shelf Location') }}</label>
                {{ html()->text('shelf_location')->class('form-control form-control-sm ' . ($errors->has('shelf_location') ? 'is-invalid' : ''))->placeholder('Enter Shelf Location') }}
                <x-validation-error :errors="$errors->first('shelf_location')" />
            </div>
        </div>



        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="status"><i class="fas fa-toggle-on"></i> {{ __('Status') }}</label>
                {{ html()->select('status', \App\Models\Product::STATUS_LIST)->class('form-select form-select-sm ' . ($errors->has('status') ? 'is-invalid' : ''))->placeholder('Select status') }}
                <x-validation-error :errors="$errors->first('status')" />
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="custom-form-group">
                <label for="sort_order"><i class="fas fa-sort-numeric-down"></i> {{ __('Sort Order') }}</label>
                {{ html()->text('sort_order')->class('form-control form-control-sm ' . ($errors->has('sort_order') ? 'is-invalid' : ''))->placeholder('Enter sort order') }}
                <x-validation-error :errors="$errors->first('sort_order')" />
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="custom-form-group">
                    <label for="description"><i class="fas fa-info-circle"></i> {{ __('Description') }}</label>
                    {{ html()->textarea('description')->id('editor')->class('form-control form-control-sm' . ($errors->has('description') ? 'is-invalid' : ''))->placeholder('Enter description') }}
                    <x-validation-error :errors="$errors->first('description')" />
                </div>
            </div>
        </div>

        <div class="row product-wrapper">
            <div class="col-md-12 mb-4">
                <div class="custom-form-group">
                    <label for="attributes"><i class="fas fa-cogs"></i> {{ __('Attributes') }}</label>
                    <div id="attributes-wrapper">
                        @if (isset($product) && $product->attributeValues->isNotEmpty())
                            @foreach ($product->attributeValues as $attributeValue)
                                <div class="attribute-row mb-3">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select name="attributes[]"
                                                class="form-select form-select-sm attribute-select">
                                                <option value="">{{ __('Select Attribute') }}</option>
                                                @foreach ($attributes as $attribute)
                                                    <option value="{{ $attribute->id }}"
                                                        data-shop-id="{{ $attribute->shop_id }}"
                                                        {{ $attribute->id == $attributeValue->attribute->id ? 'selected' : '' }}>
                                                        {{ $attribute->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <select name="attribute_values[]"
                                                class="form-select form-select-sm attribute-value-select">
                                                <option value="">{{ __('Select Value') }}</option>
                                                @foreach ($attributes as $attribute)
                                                    @foreach ($attribute->values as $value)
                                                        <option value="{{ $value->id }}"
                                                            data-attribute-id="{{ $value->attribute_id }}"
                                                            {{ $value->id == $attributeValue->id ? 'selected' : '' }}>
                                                            {{ $value->name }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-primary add-attribute-row">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger remove-attribute-row">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="attribute-row mb-3">
                                <div class="row">
                                    <div class="col-md-5">
                                        <select name="attributes[]"
                                            class="form-select form-select-sm attribute-select">
                                            <option value="">{{ __('Select Attribute') }}</option>
                                            @foreach ($attributes as $attribute)
                                                <option value="{{ $attribute->id }}"
                                                    data-shop-id="{{ $attribute->shop_id }}">{{ $attribute->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <select name="attribute_values[]"
                                            class="form-select form-select-sm attribute-value-select">
                                            <option value="">{{ __('Select Value') }}</option>
                                            @foreach ($attributes as $attribute)
                                                @foreach ($attribute->values as $value)
                                                    <option value="{{ $value->id }}"
                                                        data-attribute-id="{{ $value->attribute_id }}">
                                                        {{ $value->name }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-primary add-attribute-row">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger remove-attribute-row">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                    <x-validation-error :errors="$errors->first('attributes')" />
                </div>
            </div>
        </div>




        <div class="row justify-content-center mb-2">
            <div class="col-md-4">
                <div class="custom-form-group">
                    {{ html()->label('Product Photo')->for('photo') }}
                    <x-media-library :inputname="'photo'" :multiple="true" :displaycolumn="12" :uniqueid="1" />
                    <x-validation-error :errors="$errors->first('photo')" />
                </div>
                @if (isset($product))
                    @foreach ($product->photos as $photo)
                        <img src="{{ get_image($photo?->photo) }}" alt="image" style="width: 250px;"
                            class="img-thumbnail table-image">
                    @endforeach
                @endif
            </div>
        </div>

    </div>


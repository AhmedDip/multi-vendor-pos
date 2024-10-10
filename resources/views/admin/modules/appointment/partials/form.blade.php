
<legend>Service Details</legend>

<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Shop', 'shop_id')}}
         <x-required/>
        {{html()->select('shop_id',$shop)->class('form-select form-select-sm '. ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder(__('Select Shop'))}}
        <x-validation-error :errors="$errors->first('shop_id')"/>
    </div>
        
</div>



<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{html()->label('Category', 'category_id')}}
        <x-required/>
        <select id="category_id" name="category_id" class="form-select form-select-sm {{ $errors->has('category_id') ? 'is-invalid' : '' }}">
            <option value="">{{ __('Select Category') }}</option>
            @foreach($category_type as $shopId => $categories)
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" data-shop-id="{{ $shopId }}" {{ isset($appointment) && $appointment->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            @endforeach
        </select>
      
        <x-validation-error :errors="$errors->first('category_id')"/>
    </div>
</div>



<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{ html()->label('Service', 'product_id') }}
        <x-required/>
        <select id="product_id" name="product_id"  class="form-select form-select-sm required select2{{ $errors->has('product_id') ? 'is-invalid' : '' }}">
            <option value="">{{ __('Select Service') }}</option>
        </select>
      
        <x-validation-error :errors="$errors->first('product_id')"/>
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




<legend>Customer Details</legend>
<div class="mb-4 col-md-4">
    <div class="custom-form-group">
        {{html()->label('Phone', 'phone')}}
        <x-required/>
        {{html()->text('phone')->class('form-control form-control-sm '. ($errors->has('phone') ? 'is-invalid' : ''))->placeholder(__('Enter Phone'))}}
        <x-validation-error :errors="$errors->first('phone')"/>
    </div>
</div>
<div class="mb-4 col-md-4">
    <div class="custom-form-group">
        {{html()->label('Name', 'name')}}
        <x-required/>
        {{html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Name'))}}
        <x-validation-error :errors="$errors->first('name')"/>
    </div>
</div>

<div class="mb-4 col-md-4">
    <div class="custom-form-group">
        {{html()->label('Email', 'email')}}
        <x-required/>
        {{html()->email('email')->class('form-control form-control-sm '. ($errors->has('email') ? 'is-invalid' : ''))->placeholder(__('Enter Email'))}}
        <x-validation-error :errors="$errors->first('email')"/>
    </div>
</div>

<div class="mb-4 col-md-12">
    <div class="custom-form-group">
        {{html()->label('Comment', 'message')}}
        <x-required/>
        {{html()->textarea('message')->class('form-control form-control-sm '. ($errors->has('message') ? 'is-invalid' : ''))->placeholder(__('Enter Your message here...'))}}
        <x-validation-error :errors="$errors->first('message')"/>
    </div>
</div>





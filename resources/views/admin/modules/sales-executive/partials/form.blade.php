<div class="row">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="name">{{ __('Sales Executive Name') }}</label>
            {{ html()->text('name')->class('form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''))->placeholder('Enter Sales Executive name') }}
            <x-validation-error :errors="$errors->first('name')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="email">{{ __('Email') }}</label>
            {{ html()->email('email')->class('form-control form-control-sm ' . ($errors->has('email') ? 'is-invalid' : ''))->placeholder('Enter email') }}
            <x-validation-error :errors="$errors->first('email')" />
        </div>
    </div>
</div>




<div class="row">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="phone">{{ __('Phone') }}</label>
            {{ html()->text('phone')->class('form-control form-control-sm ' . ($errors->has('phone') ? 'is-invalid' : ''))->placeholder('Enter phone') }}
            <x-validation-error :errors="$errors->first('status')" />
        </div>
    </div>


    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="password">{{ __('Password') }}</label>
            {{ html()->password('password')->class('form-control form-control-sm ' . ($errors->has('password') ? 'is-invalid' : ''))->placeholder('Enter password') }}
            <x-validation-error :errors="$errors->first('password')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="status">{{ __('Status') }}</label>
            {{ html()->select('status', \App\Models\User::STATUS_LIST)->class('form-select form-select-sm ' . ($errors->has('status') ? 'is-invalid' : ''))->placeholder('Select status') }}
            <x-validation-error :errors="$errors->first('status')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="shop_id">{{ __('Shop') }}</label>
            {{ html()->select('shop_id', $shops, $selected_shop)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->placeholder('Select shop') }}
            <x-validation-error :errors="$errors->first('shop_id')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="role_id">{{ __('Role') }}</label>
            {{ html()->select('role_id[]', $emp_roles, $selected_roles)->class('form-select form-select-sm select2' . ($errors->has('role_id') ? 'is-invalid' : ''))->placeholder('Select role')->multiple() }}
            <x-validation-error :errors="$errors->first('role_id')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            <label for="address">{{ __('Address') }}</label>
            {{ html()->textarea('address')->class('form-control form-control-sm ' . ($errors->has('address') ? 'is-invalid' : ''))->placeholder('Enter address') }}
            <x-validation-error :errors="$errors->first('address')" />
        </div>
    </div>

</div>



<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="custom-form-group">
            {{ html()->label('Sales Executive Photo')->for('photo') }}
            <x-media-library :inputname="'photo'" :multiple="false" :displaycolumn="12" :uniqueid="1" />
            <x-validation-error :errors="$errors->first('photo')" />
        </div>
        @isset($sales_executive?->photo?->photo)
            <img src="{{ get_image($sales_executive?->photo?->photo) }}" alt="image" class="img-thumbnail mb-2">
        @endisset
    </div>
</div>

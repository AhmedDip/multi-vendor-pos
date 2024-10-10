<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-head text-center">
                <h5><i class="fas fa-wallet me-2"></i> {{ __('Add Balance') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="custom-form-group">
                            <label for="shop_id" class="form-label">
                                <i class="fas fa-store me-2"></i> {{ __('Shop') }}
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i
                                            class="fas fa-shopping-basket"></i></span>
                                </div>
                                {{ html()->select('shop_id', $shops)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder('Select Shop') }}
                            </div>
                            <x-validation-error :errors="$errors->first('shop_id')" />
                        </div>
                    </div>

                    <div class="col-md-12 mb-4">
                        <div class="custom-form-group">
                            <label for="opening_balance" class="form-label">
                                <i class="fas fa-money-bill-wave me-2"></i> {{ __('Opening Balance') }}
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                {{ html()->text('opening_balance')->class('form-control form-control-sm ' . ($errors->has('opening_balance') ? 'is-invalid' : ''))->id('opening_balance')->placeholder('Enter Opening Balance') }}
                            </div>
                            <x-validation-error :errors="$errors->first('opening_balance')" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

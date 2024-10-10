{{ html()->form('GET', route('top-customer-report'))->id('search_form')->open() }}

<div class="mb-4 row justify-content-center align-items-end">
    <div class="col-md-3">
        <label for="shop_id">{{ __('Shop') }}</label>
        {{html()->select('shop_id', $shops, $search['shop_id'] ?? null)->class('form-select form-select-sm select2')->placeholder('Select Shop')}}
    </div>

    <div class="col-md-3">
        <label for="start_date">{{ __('Start Date') }}</label>
        <div class="input-group">
            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
            {{ html()->date('start_date', $search['start_date'] ?? null)
                ->class('form-control form-control-sm')
            }}
        </div>
    </div>

    <div class="col-md-3">
        <label for="end_date">{{ __('End Date') }}</label>
        <div class="input-group">
            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
            {{ html()->date('end_date', $search['end_date'] ?? null)
                ->class('form-control form-control-sm')
            }}
        </div>
    </div>

    <div class="mt-2 col-md-4 mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="d-grid">
                    <button id="reset_fields" class="btn btn-sm btn-warning" type="reset">
                        <i class="fa-solid fa-rotate"></i> {{ __('Reset ') }}
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-grid">
                    <button class="btn btn-success btn-sm" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i> {{ __('Generate') }}
                    </button>
                </div>
            </div>   
        </div>
    </div>

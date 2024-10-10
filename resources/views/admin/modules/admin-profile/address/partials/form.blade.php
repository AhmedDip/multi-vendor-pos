<div class="row justify-content-center">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('name', 'Name') !!}
            <x-required/>
            {!! Form::text('name', $address->name ?? \Illuminate\Support\Facades\Auth::user()->name, ['class' => 'form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter name']) !!}
            <x-validation-error :errors="$errors->first('name')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('phone', 'Phone') !!}
            <x-required/>
            {!! Form::text('phone', $address->phone ?? \Illuminate\Support\Facades\Auth::user()->phone, ['class' => 'form-control form-control-sm '. ($errors->has('phone') ? 'is-invalid' : ''), 'placeholder' => 'Enter phone']) !!}
            <x-validation-error :errors="$errors->first('phone')"/>
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="custom-form-group">
            {!! Form::label('address', 'Address') !!}
            <x-required/>
            {!! Form::textarea('address', null, ['class' => 'form-control w-100 '. ($errors->has('address') ? 'is-invalid' : ''), 'placeholder' => 'Enter address', 'rows'=>2]) !!}
            <x-validation-error :errors="$errors->first('address')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('division_id', 'Select Division') !!}
            <x-required/>
            {!! Form::select('division_id', $divisions ,null, ['class' => 'form-select '. ($errors->has('division_id') ? 'is-invalid' : ''), 'placeholder' => 'Select division']) !!}
            <x-validation-error :errors="$errors->first('division_id')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('district_id', 'Select District') !!}
            <x-required/>
            <select
                name="district_id"
                class="form-select select2 {{$errors->has('district_id') ? 'is-invalid' : ''}}"
                id="district_id">
                <option value="">Select District</option>
            </select>
            <x-validation-error :errors="$errors->first('district_id')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('thana_id', 'Select Thana') !!}
            <x-required/>
            <select
                name="thana_id"
                class="form-select select2 {{$errors->has('thana_id') ? 'is-invalid' : ''}}"
                id="thana_id">
                <option value="">Select Thana</option>
            </select>
            <x-validation-error :errors="$errors->first('thana_id')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('landmark', 'Landmark') !!}
            {!! Form::text('landmark', null, ['class' => 'form-control form-control-sm '. ($errors->has('landmark') ? 'is-invalid' : ''), 'placeholder' => 'Enter landmark']) !!}
            <x-validation-error :errors="$errors->first('landmark')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('address_type', 'Select address type') !!}
            <x-required/>
            {!! Form::select('address_type', \App\Models\Address::ADDRESS_TYPE_LIST ,null, ['class' => 'form-select '. ($errors->has('address_type') ? 'is-invalid' : ''), 'placeholder' => 'Select address type']) !!}
            <x-validation-error :errors="$errors->first('address_type')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {!! Form::label('status', 'Select status') !!}
            <x-required/>
            {!! Form::select('status', \App\Models\Address::STATUS_LIST ,null, ['class' => 'form-select '. ($errors->has('status') ? 'is-invalid' : ''), 'placeholder' => 'Select status']) !!}
            <x-validation-error :errors="$errors->first('status')"/>
        </div>
    </div>
    <div class="col-md-3 mb-2">
        <div class="custom-form-group">
            <div class="form-check">
                {!! Form::checkbox('is_default', 1 ,null, ['class' => 'form-check-input '. ($errors->has('is_default') ? 'is-invalid' : ''), 'placeholder' => 'Select status']) !!}
                {!! Form::label('is_default', 'Is default', ['class' => 'form-check-label']) !!}
                <x-validation-error :errors="$errors->first('is_default')"/>
            </div>
        </div>
    </div>
</div>

<div class="d-none" id="districts" data-districts="{{json_encode($districts)}}"></div>
<div class="d-none" id="thanas" data-thanas="{{json_encode($thanas)}}"></div>
<div class="d-none" id="address_data" data-address="{{isset($address) ? json_encode($address) : null }}"></div>


@push('script')
    <script>
        let districts = $('#districts').data('districts');
        let thanas = $('#thanas').data('thanas');
        let address = $('#address_data').data('address');
        console.log(address)
        const handleDistrict = (division_id) => {
            let district_element = $('#district_id');
            district_element.empty();
            district_element.append('<option value="">Select District</option>');
            let selected_districts = districts.filter((district) => district.division_id == division_id);
            selected_districts.forEach((district) => {
                district_element.append(`<option ${address.district_id == district.id ? 'selected' : ''} value="${district.id}">${district.name}</option>`);
            })
        }

        const handleThana = (district_id) => {
            let thana_element = $('#thana_id');
            thana_element.empty();
            thana_element.append('<option value="">Select Thana</option>');
            let selected_thanas = thanas.filter((thana) => thana.district_id == district_id);
            selected_thanas.forEach((thana) => {
                thana_element.append(`<option ${address.thana_id == thana.id ? 'selected' : ''} value="${thana.id}">${thana.name}</option>`);
            })
        }

        $('#district_id').on('change', function () {
            handleThana($(this).val())
        })
        $('#division_id').on('change', function () {
            handleDistrict($(this).val())
            let thana_element = $('#thana_id');
            thana_element.empty();
            thana_element.append('<option value="">Select Thana</option>');
        });
        if (address.division_id) {
            handleDistrict(address.division_id)
        }
        if (address.district_id) {
            handleThana(address.district_id)
        }
    </script>
@endpush

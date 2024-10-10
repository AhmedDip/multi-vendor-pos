<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{ html()->label('Card Number', 'card_no') }}
        <x-required />
        {{ html()->text('card_no')->class('form-control form-control-sm ' . ($errors->has('card_no') ? 'is-invalid' : ''))->placeholder(__('Enter Card No')) }}
        <x-validation-error :errors="$errors->first('card_no')" />
    </div>
</div>

<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{ html()->label('Shop', 'shop_id') }}
        <x-required />
        {{ html()->select('shop_id', $shop)->class('form-select form-select-sm ' . ($errors->has('shop_id') ? 'is-invalid' : ''))->id('shop_id')->placeholder(__('Select Shop')) }}
        <x-validation-error :errors="$errors->first('shop_id')" />
    </div>

</div>
<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{ html()->label('Membership Card Type', 'membership_card_type_id') }}
        <x-required />
        <select id="membership_card_type_id" name="membership_card_type_id"
    class="form-select form-select-sm {{ $errors->has('membership_card_type_id') ? 'is-invalid' : '' }}">
    <option value="">{{ __('Select Membership Card Type') }}</option>
    @foreach ($membership_card_type as $shopId => $membershipCards)
        @foreach ($membershipCards as $membership_card)
            <option value="{{ $membership_card->id }}" data-shop-id="{{ $shopId }}"
                {{ old('membership_card_type_id', $selectedMembershipCardTypeId ?? '') == $membership_card->id ? 'selected' : '' }}>
                {{ $membership_card->card_type_name }}
            </option>
        @endforeach
    @endforeach
</select>

        <x-validation-error :errors="$errors->first('membership_card_type_id')" />
    </div>
</div>


<div class="mb-4 col-md-3">
    <div class="custom-form-group">
        {{ html()->label('Status', 'status') }}
        <x-required />
        {{ html()->select('status', \App\Models\MembershipCard::STATUS_LIST)->class('form-select form-select-sm ' . ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Status')) }}
        <x-validation-error :errors="$errors->first('status')" />
    </div>
</div>

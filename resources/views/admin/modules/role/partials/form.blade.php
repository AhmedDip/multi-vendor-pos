{{-- <div class="custom-form-group">
    {!! Form::label('name', 'Role Name') !!} <x-required/>
    {!! Form::text('name', null, ['class' => 'form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter role name']) !!}
   <x-validation-error :errors="$errors->first('name')"/>
</div>
 --}}


 <div class="custom-form-group">
    {{-- {!! Form::label('name', 'Role Name') !!} <x-required/> --}}
    <label for="name">{{__('Role Name')}}</label>
    {{-- {!! Form::text('name', null, ['class' => 'form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''), 'placeholder' => 'Enter role name']) !!} --}}
    {{ html()->text('name')->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder('Enter role name')}}
   <x-validation-error :errors="$errors->first('name')"/>
</div>


<div class="custom-form-group mt-2">
    <label for="type">{{__('Role Type')}}</label>
    {{ html()->select('role_type', $role_type)->class('form-control form-control-sm '. ($errors->has('role_type') ? 'is-invalid' : ''))->placeholder('Select Role Type')}}
    <x-validation-error :errors="$errors->first('role_type')"/>
</div>
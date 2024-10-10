@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{-- {!! Form::model($role,['route' => ['role.update', $role->id], 'method' => 'put', 'id' => 'create_form']) !!} --}}
            {{-- {{html()->modelForm($menu, 'PUT', route('menu.update', $menu->id))->id('create_form')->open()}} --}}
            {{ html()->modelForm($role, 'PUT', route('role.update', $role->id))->id('create_form')->open() }}
            <div class="row justify-content-center align-items-end">
                <div class="col-md-6">
                    @include('admin.modules.role.partials.form')
                </div>
                <div class="col-md-2">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>
            {{html()->form()->close()}}
        </div>
    </div>

@endsection

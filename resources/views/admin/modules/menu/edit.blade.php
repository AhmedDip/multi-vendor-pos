@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            {{html()->modelForm($menu, 'PUT', route('menu.update', $menu->id))->id('create_form')->open()}}

            <div class="row justify-content-center align-items-end">
                @include('admin.modules.menu.partials.form')
                <div class="row justify-content-center">
                    <div class="col-md-2">
                        <x-submit-button :type="'update'"/>
                    </div>
                </div>
            </div>

            {{html()->form()->close()}}
        </div>
    </div>

@endsection

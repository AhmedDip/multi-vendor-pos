@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-4">
        <div class="card-body">
            {{html()->modelForm($user, 'post',route('profile.store'))->id('create_form')->acceptsFiles()->open()}}
            <div class="row justify-content-center align-items-end">
                <div class="col-md-12">
                    @include('admin.modules.admin-profile.partials.form')
                </div>
                <div class="col-md-3 mt-4">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>
           {{html()->form()->close()}}
        </div>
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

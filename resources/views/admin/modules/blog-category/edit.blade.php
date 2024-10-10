@extends('admin.layouts.app')
@section('content')
<div class="card body-card">
    <div class="card-body">
        {{ html()->modelForm($blog_category, 'PUT', route('blog-category.update', $blog_category->id))->id('create_form')->open() }}
        <div class="row mb-2 justify-content-center align-items-end">
            <div class="col-md-12">
                @include('admin.modules.blog-category.partials.form')
            </div>
            <div class="col-md-3">
                <x-submit-button :type="'update'" />
            </div>
        </div>
        {{ html()->form()->close() }}
    </div>
</div>

@endsection
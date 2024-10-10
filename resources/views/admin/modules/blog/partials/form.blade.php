<div class="row justify-content-center mt-4">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Blog name'))->for('name') }}
            <x-required />
            {{ html()->text('name')->class('form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Blog name') ) }}
            <x-validation-error :errors="$errors->first('name')" />
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Slug'))->for('slug') }}
            <x-required />
            {{ html()->text('slug')->class('form-control form-control-sm ' . ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter Slug') ) }}
            <x-validation-error :errors="$errors->first('slug')" />
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Category'))->for('categories') }}
            <x-required />
            {{ html()->select('categories[]', $categories, isset($blog) ? $blog->categories->pluck('id') : null)->class('form-select form-control-sm select2' . ($errors->has('categories') ? 'is-invalid' : ''))->multiple() }}
            <x-validation-error :errors="$errors->first('categories')" />
        </div>
    </div>
 
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Tag'))->for('tag') }}
            <x-required />
            {{ html()->text('tag')->class('form-control form-control-sm ' . ($errors->has('tag') ? 'is-invalid' : ''))->placeholder(__('Enter Tag') ) }}
            <x-validation-error :errors="$errors->first('tag')" />
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Summary'))->for('summary') }}
            {{ html()->textarea('summary')->class('form-control form-control-sm' . ($errors->has('summary') ? 'is-invalid' : ''))->placeholder(__('Enter Summary')) }}
            <x-validation-error :errors="$errors->first('summary')" />
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Content'))->for('content') }}
            <x-required />
            {{ html()->textarea('content')->class('tinymce form-control form-control-sm' . ($errors->has('content') ? 'is-invalid' : ''))->placeholder(__('Enter Content'))->rows(14) }}
            <x-validation-error :errors="$errors->first('content')" />
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Status'))->for('status') }}
            <x-required />
            {{ html()->select('status', $status, null)->class('form-select form-control-sm ' . ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Status')) }}
            <x-validation-error :errors="$errors->first('status')" />
        </div>
    </div>
    <div class="col-md-3 mb-4 mt-4">
        <div class="custom-form-group">
            {{ html()->checkbox('is_comment_allowed', 1)->class('form-check-input ' . ($errors->has('is_comment_allowed') ? 'is-invalid' : ''))->id('is_comment_allowed')}}
            {{ html()->label(__('Is Comment Allowed'))->for('is_comment_allowed') }}
            <x-validation-error :errors="$errors->first('is_comment_allowed')" />
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="custom-form-group">
            {{ html()->label(__('Photo'))->for('photo') }}
            <x-media-library
            :inputname="'photo'"
            :multiple="false"
            :displaycolumn="12"
            :uniqueid="1"
        />
            <x-validation-error :errors="$errors->first('photo')" />
        </div>
    
        @isset($blog->photo->photo)
            <img src="{{ get_image($blog?->photo?->photo) }}" alt="image" class="img-thumbnail">
        @endisset
    </div>
</div>
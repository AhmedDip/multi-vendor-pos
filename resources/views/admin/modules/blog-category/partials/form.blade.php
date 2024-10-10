<div class="row justify-content-center mt-4">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Blog Category Name'))->for('name') }}
            <x-required />
            {{ html()->text('name')->class('form-control form-control-sm ' . ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter Blog Category Name') ) }}
            <x-validation-error :errors="$errors->first('name')" />
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Slug'))->for('slug') }}
            <x-required />
            {{ html()->text('slug')->class('form-control form-control-sm ' . ($errors->has('slug') ? 'is-invalid' : ''))->placeholder(__('Enter slug') ) }}
            <x-validation-error :errors="$errors->first('slug')" />
        </div>
    </div>
 
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Parent Category'))->for('parent_id') }}
            {{ html()->select('parent_id', $categories, null)->class('form-select form-control-sm ' . ($errors->has('parent_id') ? 'is-invalid' : ''))->placeholder(__('Select Parent Category')) }}
            <x-validation-error :errors="$errors->first('parent_id')" />
        </div>
    </div>
    <div class="col-md-12 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Description'))->for('description') }}
            {{ html()->textarea('description')->id('editor')->class('form-control form-control-sm' . ($errors->has('description') ? 'is-invalid' : ''))->placeholder(__('Enter Description')) }}
            <x-validation-error :errors="$errors->first('description')" />
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{ html()->label(__('Display Order'))->for('display_order') }}
            {{ html()->number('display_order')->class('form-control form-control-sm ' . ($errors->has('display_order') ? 'is-invalid' : ''))->placeholder(__('Enter Display Order')) }}
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{  html()->label(__('Status'))->for('status')}}
            <x-required />
            {{ html()->select('status', $status, null)->class('form-select form-control-sm ' . ($errors->has('status') ? 'is-invalid' : ''))->placeholder(__('Select Status')) }}
            <x-validation-error :errors="$errors->first('status')" />
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
        @isset($blog_category?->photo?->photo)
            <img src="{{ get_image($blog_category?->photo?->photo) }}" alt="image" class="img-thumbnail">
        @endisset
    </div>
</div>
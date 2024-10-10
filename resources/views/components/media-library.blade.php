<div class="image-upload-area mb-4">
    <div class="input-group">
        <div class="input-group-text p-0">
            <button
                class="btn btn-primary image-upload-button"
                type="button"
                data-input-target="{{$inputid.'_'.$uniqueid}}"
                data-allow-multiple="{{$multiple}}"
                data-display-target="{{$displayid.'_'.$uniqueid}}"
                data-unique-id="{{$uniqueid}}"
            >
                Upload Image
            </button>
        </div>
        <input
            type="text"
            readonly
            name="{{$inputname}}"
            id="media-library-image-input_{{$uniqueid}}"
            class="form-control media-library-image-input"
        />
    </div>
</div>
<div class="d-none get_routes_for_media_library"
     id="get_routes"
     data-get_media_library="{{route('get_media_library')}}"
     data-create_new_directory="{{route('create_new_directory')}}"
     data-upload_media_library="{{route('upload_media_library')}}"
     data-delete_media_library="{{route('delete_media_library')}}"
     data-display_column="{{$displaycolumn}}"
>
</div>
@if($preview)
    <div class="row" id="media-library-preview-small-img_{{$uniqueid}}"></div>
@endif
@include('admin.global-partials.image-upload')


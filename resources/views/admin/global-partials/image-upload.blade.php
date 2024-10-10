<div class="modal fade" id="image_upload_modal_{{$uniqueid}}" tabindex="-1" aria-labelledby="image_upload_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="modal-header py-2 px-4 position-relative">
                <h1 class="modal-title fs-5" id="image_upload_modal_label_{{$uniqueid}}">Choose Image</h1>
                <div style="display: none" class="progress_bar" id="progressBar_{{$uniqueid}}"></div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body image-preview-modal-body">
                <div class="loader" id="loader_{{$uniqueid}}">
                    <img src="{{asset('admin_assets/images/loader.svg')}}" alt="loader">
                </div>
                <div class="image-container" style="display: none" id="media-library-preview-container_{{$uniqueid}}">
                    <div class="row" id="media-library-container_{{$uniqueid}}">
                        <div class="col-md-3">
                            <ul class="list-group media-gallery-menu" id="media-gallery-menu_{{$uniqueid}}"></ul>
                        </div>
                        <div class="col-md-9 position-relative">
                            <div class="row media-library-preview" id="media-library-preview_{{$uniqueid}}"></div>
                            <div class="row justify-content-center media-library-load-more-area" id="media-library-load-more-area_{{$uniqueid}}">
                                <div class="col-md-3">
                                    <button
                                        type="button"
                                        class="btn btn-success btn-sm mt-2 media-library-load-more"
                                        id="media-library-load-more_{{$uniqueid}}"
                                    >
                                        Load more
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="expendable-area" id="expendable-area_{{$uniqueid}}" style="display: none">
                    <button
                        class="btn btn-info mb-1 media-library-upload-button-expended"
                        type="button"
                        id="media-library-upload-button-expended_{{$uniqueid}}"
                    >
                        <i class="fa-solid fa-upload"></i> Upload
                    </button>
                    <button
                        class="btn btn-warning media-library-create-new-folder"
                        type="button"
                        id="media-library-create-new-folder_{{$uniqueid}}"
                    >
                        <i class="fa-solid fa-folder-open"></i> Create Folder
                    </button>
                </div>
                <button
                    class="btn btn-success media-library-upload-container"
                    id="media-library-upload-button_{{$uniqueid}}"
                    type="button"
                >
                    <i class="fa-solid fa-plus"></i>
                </button>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="upload-photo-modal_{{$uniqueid}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel_{{$uniqueid}}">Upload Photo</h1>
                <button type="button" id="image_upload_modal_close_{{$uniqueid}}" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="upload-photo-input">Upload Photo</label>
                <input type="file" class="form-control upload-photo-input" id="upload-photo-input_{{$uniqueid}}" accept="image/*" multiple>
                <p><small class="text-danger" id="upload-photo-error_{{$uniqueid}}"></small></p>
                <button type="button" class="btn btn-success mt-3" id="upload-photo-submit_{{$uniqueid}}">Upload</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="media-library-create-new-folder-modal_{{$uniqueid}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content custom-modal-content">
            <div class="modal-header py-2">
                <h1 class="modal-title fs-5" id="exampleModalLabel_{{$uniqueid}}">Create new folder</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="media-library-create-new-folder-input">Folder Name</label>
                <p><small class="text-danger">Folder name must not contain any space</small></p>
                <input type="text" class="form-control" id="media-library-create-new-folder-input_{{$uniqueid}}">
                <p><small class="text-danger" id="media-library-create-new-folder-error_{{$uniqueid}}"></small></p>
                <button type="button" class="btn btn-success mt-3 create-new-folder-submit" id="create-new-folder-submit_{{$uniqueid}}">Create</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade media-library-preview-large-container-modal" id="media-library-preview-large-container-modal_{{$uniqueid}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="modal-body text-center p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <img class="img-fluid mx-auto media-library-preview-large-img" id="media-library-preview-large-img_{{$uniqueid}}">
            </div>
        </div>
    </div>
</div>


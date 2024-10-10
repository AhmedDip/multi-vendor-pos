<div>
    <div class="image-upload-container">
        <img src="{{$photo ?? asset('images/assets/default.jpg')}}" alt="" class="image-preview img-thumbnail">
        <div class="overly"></div>
        <i class="fa-solid fa-camera fa-3x"></i>
        <input type="file" class="image-upload-input" name="{{$name}}" style="display: none">
    </div>
</div>

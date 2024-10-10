<!doctype html>
<html lang="en">
<head>
    @include('admin.layouts.partials.head')
</head>
<body>

<div class="body-wrapper">
    @include('admin.layouts.partials.left-side-bar')
    <div class="main-content" id="main_content">
        @include('admin.layouts.partials.top-bar')
        @include('admin.layouts.partials.bread-crumb')
        @yield('content')
    </div>
</div>

<script src="{{asset('plugins/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>
<script src="{{asset('plugins/sweet-alert/sweetalert2@11.js')}}"></script>
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<script src="{{asset('plugins/tom-select/tom-select.complete.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"
        integrity="sha512-NQfB/bDaB8kaSXF8E77JjhHG5PM6XVRxvHzkZiwl3ddWCEPBa23T76MuWSwAJdMGJnmQqM0VeY9kFszsrBEFrQ==" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
<script src="{{asset('theme/js/common-scripts.js')}}"></script>
@if(theme() == config('constants.themes.theme_default'))
    <script src="{{asset('theme/js/default-theme.js')}}"></script>
@else
    <script src="{{asset('theme/js/theme-alpha.js')}}"></script>
@endif

<script src="{{asset('admin_assets/js/media_library.js')}}"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.2.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });

    });
</script>





@stack('scripts')

</body>
</html>

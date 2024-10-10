<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title') | {{config('app.name')}}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
          integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous"
          referrerpolicy="no-referrer"/>
    <link href="{{asset('plugins/bootstrap-5.3.3-dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('auth/style.css')}}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-4">
            <img src="{{asset('images/assets/demo/login-banner.png')}}" class="ad-banner" alt="banner">
        </div>
        <div class="col-md-8">
            <div class="language-switch">
                {{ html()->form('POST', route('dashboard.switch-language'))->id('language_change_form')->open() }}
                <div class="top-bar-menu-list">
                    @php
                        $language_list = [];
                        foreach (config('constants.languages') as $key => $value) {
                            $language_list[$key] = __($value);
                        }
                    @endphp
                    {{html()->select('locale', $language_list , app()->getLocale())->id('locale')->class('form-select')}}
                </div>
                {{html()->form()->close()}}
            </div>
            <div class="position-relative w-100 h-100">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<script src="{{asset('plugins/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js')}}"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="{{asset('plugins/sweet-alert/sweetalert2@11.js')}}"></script>
<script>
    $('#locale').on('change', function () {
        $('#language_change_form').submit();
    })

    if ('{{session('status')}}'){
        Swal.fire({
            position: "top-end",
            icon: "success",
            toast: true,
            title: "{{session('status')}}",
            showConfirmButton: false,
            timer: 3000
        });
    }

</script>
</body>
</html>

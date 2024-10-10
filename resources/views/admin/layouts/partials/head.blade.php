<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Welcome | {{ $cms_content['module'] ?? 'Dashboard' }} | {{ config('app.name') }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
<link href="{{ asset('plugins/bootstrap-5.3.3-dist/css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('admin_assets/css/media_library.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" />
<link href="{{ asset('theme/css/common-css.css') }}" rel="stylesheet" type="text/css" />
@if (theme() == config('constants.themes.theme_default'))
    <link href="{{ asset('theme/css/default-theme.css') }}" rel="stylesheet" type="text/css" />
@else
    <link href="{{ asset('theme/css/theme-alpha.css') }}" rel="stylesheet" type="text/css" />
@endif
<link rel="icon" type="image/x-icon" href="{{ asset('images/assets/favicon.png') }}">
@stack('css')

<style>
    p.ck-placeholder {
        height: 200px !important;
    }

    .ck-editor__editable_inline {
        min-height: 200px !important;
    }

    .card-head {
        font-size: 1.5rem;
        font-weight: bold;
        color: #252525;
        padding: 0.3rem 0;
        background: #cfd6f3;
        text-align: center;
    }

    .badge-light {
        background-color: #f8f9fc;
        color: #5a5c69;
        font-size: 0.95rem;
        padding: 0.35rem 0.75rem;
    }
</style>

<div class="top-bar">
    <div class="side-bar-toggle" id="side-bar-toggle">
        <i class="fa-solid fa-chevron-left"></i>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-6">
                    <div class="search-form">
                        <input
                            type="search"
                            class="form-control"
                            id="search_input_topbar"
                        >
                        <div class="search-form-content" id="search_content_topbar">
                            <i class="fa-solid fa-magnifying-glass"></i> {{__('Search')}}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
            </div>
        </div>
        <div class="col-lg-4">
            <div class="right-panel-top-bar">
                <div style="display:none;" class="theme-switcher me-4">
                    {{html()->form('POST', route('dashboard.switch-theme'))->open()}}
                    <div class="checkbox">
                        <input name="theme_id" value="2" type="checkbox" id="change_theme"
                               style="display:none" {{Cache::has('theme') && Cache::get('theme') == config('constants.themes.theme_alpha') ? 'checked' : ''}}>
                        <label for="change_theme" class="toggle"><span></span></label>
                    </div>
                    {{html()->form()->close()}}

                </div>
                <div class="chat-icon me-4">
                    @if(true)
                        <img src="{{asset('images/assets/icons/email_active.svg')}}" alt="Email icon">
                    @else
                        <img src="{{asset('images/assets/icons/email.svg')}}" alt="Email icon">
                    @endif
                </div>
                <div class="notification-icon me-4">
                    @if(true)
                        <img src="{{asset('images/assets/icons/notification_active.svg')}}" alt="Notification icon">
                    @else
                        <img src="{{asset('images/assets/icons/notification.svg')}}" alt="Notification icon">
                    @endif
                </div>
                <div class="user-profile me-4">
                    <img src="{{get_profile_photo()}}" alt="Profile photo">
                    <div class="profile-photo-dropdown-container">
                        <div class="profile-photo-dropdown">
                            <ul>
                                <li><a href="{{route('profile.create')}}"><i class="fa-regular fa-user"></i> {{__('Profile')}}</a></li>
                                <li><a href=""><i class="fa-solid fa-gears"></i> {{__('Settings')}}</a></li>
                                <li><a href="{{route('change-password')}}"><i class="fa-solid fa-key"></i> {{__('Change password')}}</a></li>
                                <li>
                                    {{html()->form('POST', route('logout'))->open()}}
                                    <button type="button" id="logout_submit_button" class="btn btn-sm btn-outline-danger"><i
                                            class="fa-solid fa-power-off"></i> {{__('Logout')}}
                                    </button>
                                    {{html()->form()->close()}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $('#logout_submit_button').on('click', function () {
            Swal.fire({
                title: "{{__('Are you sure?') }}",
                text: "{{__('You won\'t be able to revert this!') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{__('Yes, logout!') }}",
                cancelButtonText: "{{__('No, cancel!') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#logout_submit_button').prop('disabled', true);
                    $('#logout_submit_button').html('<i class="fa-solid fa-spinner fa-spin"></i> {{__('Logging out...')}}');
                    $(this).closest('form').submit();
                }
            });
        })

        $('#locale').on('change', function () {
            $('#language_change_form').submit();
        })
    </script>
@endpush

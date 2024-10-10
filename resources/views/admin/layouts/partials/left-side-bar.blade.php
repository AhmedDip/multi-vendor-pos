@php
    $sidebar_menus = \App\Manager\UI\MenuManager::get_menus();
@endphp
<div class="left-side-bar" id="left_side_bar">
    <a href="{{route('dashboard')}}" id="dashboard_link">
        <div class="logo-area">
            <div class="logo-icon">
                <img src="{{asset('images/assets/icon.webp')}}" alt="logo icon">
            </div>
            <div class="main-logo">
                <img src="{{asset('images/assets/logo.webp')}}" alt="logo">
            </div>
        </div>
    </a>
    <nav>
        <ul>
            @foreach($sidebar_menus as $sidebar_menu)
                @if(isset($sidebar_menu->sub_menus) && count($sidebar_menu->sub_menus) > 0 && \App\Manager\UI\MenuManager::check_permission_sub_menu($sidebar_menu->sub_menus))
                    <li>
                        <div class="menu-inline">
                            <a href="{{!empty($sidebar_menu->route) ? route($sidebar_menu->route).$sidebar_menu->query_string : 'javascript: void(0)'}}">
                                {!! $sidebar_menu->icon !!}
                                <span>{{$sidebar_menu->name}}</span>
                            </a>
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                        <div class="sub-menu-container">
                            <ul class="sub-menu" aria-expanded="false">
                                @foreach($sidebar_menu->sub_menus as $sub_menu)
                                    @if(isset($sub_menu->sub_menus) && count($sub_menu->sub_menus) > 0 && \App\Manager\UI\MenuManager::check_permission_sub_menu($sub_menu->sub_menus))
                                        <li>
                                            <div class="menu-inline">
                                                <a href="">
                                                    {!! $sub_menu->icon !!}
                                                    {{$sub_menu->name}}
{{--                                                    <span class="menu-arrow left-has-menu"><i class="mdi mdi-chevron-right"></i></span>--}}
                                                </a>
{{--                                                <i class="fa-solid fa-chevron-right"></i>--}}
                                            </div>
                                            <div class="sub-menu-container">
                                                <ul class="sub-menu" aria-expanded="false">
                                                    @foreach($sub_menu->sub_menus as $sub_sub_menu)
                                                        @can($sub_sub_menu->route)
                                                            <li>
                                                                <div class="menu-inline">
                                                                    <a href="{{!empty($sub_sub_menu->route) ? route($sub_sub_menu->route).$sub_sub_menu->query_string : 'javascript: void(0)'}}">{{$sub_sub_menu->name}}</a>
                                                                </div>
                                                            </li>
                                                        @endcan
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                    @else
                                        @if(!empty($sub_menu->route))
                                            @can($sub_menu->route)
                                                <li>
                                                    <div class="menu-inline">
                                                        <a href="{{Route::has($sub_menu->route) ? route($sub_menu->route).$sub_menu->query_string : 'javascript: void(0)'}}">
                                                            {!!  $sub_menu->icon !!}
                                                            <span>{{$sub_menu->name}}</span>
{{--                                                            <i class="fa-solid fa-chevron-right"></i>--}}
                                                        </a>
                                                    </div>
                                                </li>
                                            @endcan
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </li>
                @else
                    @if(!empty($sidebar_menu->route))
                        @can($sidebar_menu->route)
                        <a style="text-decoration: none;color:black;" href="{{ route($sidebar_menu->route).$sidebar_menu->query_string}}">
                            <li class="single-li">
                                    {!! $sidebar_menu->icon !!}
                                    <span>{{$sidebar_menu->name}}</span>
                                </li>
                            </a>
                        @endcan
                    @endif
                @endif
            @endforeach
        </ul>
    </nav>
</div>

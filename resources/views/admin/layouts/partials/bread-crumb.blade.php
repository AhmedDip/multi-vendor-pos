<div class="bread-crumb p-2">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{route('dashboard')}}"><i class="fa-solid fa-house"></i> {{__('Dashboard')}}</a>
            </li>
            @isset($cms_content['module'])
                <li class="breadcrumb-item">
                    <a href="{{$cms_content['module_url'] ?? '#'}}">{{$cms_content['module']}}</a>
                </li>
            @endisset
            @isset($cms_content['sub_module'])
                <li class="breadcrumb-item">
                    <a href="{{$cms_content['sub_module_url'] ?? 'Dashboard'}}">{{$cms_content['sub_module']}}</a>
                </li>
            @endisset
            @isset($cms_content['active_title'])
                <li class="breadcrumb-item active" aria-current="page">{{$cms_content['active_title']}}</li>
            @endisset
        </ol>
    </nav>
    @isset($cms_content['button_title'])
        <a href="{{$cms_content['button_url'] ?? '#'}}">
            <div class="action-buttons">
                <button class="btn theme-button btn-sm">
                    @isset($cms_content['button_type'])
                        @if($cms_content['button_type'] == 'create')
                            <i class="fa-solid fa-plus"></i>
                        @elseif($cms_content['button_type'] == 'list')
                            <i class="fa-solid fa-list"></i>
                        @elseif($cms_content['button_type'] == 'edit')
                            <i class="fa-solid fa-edit"></i>
                        @elseif($cms_content['button_type'] == 'show')
                            <i class="fa-solid fa-eye"></i>
                        @elseif($cms_content['button_type'] == 'delete')
                            <i class="fa-solid fa-trash"></i>
                        @elseif($cms_content['button_type'] == 'back')
                            <i class="fa-solid fa-arrow-left"></i>
                        @endif
                    @endisset
                    {{ $cms_content['button_title']}}
                </button>
            </div>
        </a>
    @endisset
</div>

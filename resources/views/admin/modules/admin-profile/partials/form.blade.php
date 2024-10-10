<div class="row justify-content-center">
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Name', 'name')}}
            <x-required/>
            {{html()->text('name', null)->class('form-control form-control-sm '. ($errors->has('name') ? 'is-invalid' : ''))->placeholder(__('Enter name'))}}
            <x-validation-error :errors="$errors->first('name')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Email', 'email')}}
            {{html()->text('email', null)->class('form-control form-control-sm '. ($errors->has('email') ? 'is-invalid' : ''))->placeholder(__('Enter email'))}}
            <x-validation-error :errors="$errors->first('email')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('Phone', 'phone')}}
            <x-required/>
            {{html()->text('phone', null)->class('form-control form-control-sm '. ($errors->has('phone') ? 'is-invalid' : ''))->placeholder(__('Enter phone'))}}
            <x-validation-error :errors="$errors->first('phone')"/>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="custom-form-group">
            {{html()->label('roles', 'Role')}}
            <x-required/>
            <p class="form-control form-control-sm">
                @forelse($user->roles as $role)
                    <span class="badge text-bg-info">{{$role->name}}</span>
                @empty
                    <span class="badge text-bg-danger">No Role</span>
                @endforelse
            </p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-4">
        <x-media-library
            :inputname="'photo'"
            :multiple="false"
            :displaycolumn="12"
            :uniqueid="1"
        />
        @php
            $photo = isset($user->profile_photo->photo) ? \Illuminate\Support\Facades\Storage::url($user->profile_photo->photo) : null;
        @endphp
        @if($photo)
            <img src="{{url($photo)}}" alt="image" class="img-thumbnail">
        @endif
    </div>
</div>

@push('script')
    {{--    <script>--}}
    {{--        $('.input-group-text').on('click', function () {--}}
    {{--            $(this).children('i').toggleClass('fa-eye-slash');--}}
    {{--            let type = $(this).siblings('input').attr('type');--}}
    {{--            if (type == 'password') {--}}
    {{--                $(this).siblings('input').attr('type', 'text');--}}
    {{--            } else {--}}
    {{--                $(this).siblings('input').attr('type', 'password');--}}
    {{--            }--}}
    {{--        })--}}
    {{--    </script>--}}
@endpush


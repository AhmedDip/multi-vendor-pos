@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-4">
        <div class="card-body">
            @include('admin.modules.role-assign.partials.search')

            <table class="table table-hover table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>{{ __('SL') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Roles') }}</th>
                    <th>{{ __('Date Time') }}
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>{{ __('Assign Role') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <x-serial :serial="$loop->iteration" :collection="$users"/>
                        </td>
                        <td>{{$user->name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone}}</td>
                        <td>
                            @php
                                $status = \App\Models\User::STATUS_LIST[$user->status] ?? null;
                            @endphp
                            @if($user->status == \App\Models\User::STATUS_ACTIVE)
                                <x-active/>
                            @else
                                <x-inactive :title="$status"/>
                            @endif
                        </td>
                        <td>
                            @forelse($user?->roles as $key=>$role)
                                <button style="background-color: rgb(223, 236, 223)"
                                        class="role-button button"><small>{{$role->name}}</small></button>
                            @empty
                                <span class="badge bg-warning">No Role</span>
                            @endforelse
                        </td>
                        <td>
                            <x-date-time :created="$user->created_at" :updated="$user->updated_at"/>
                        </td>
                        <td>
                            <button
                                data-roles="{{json_encode($user->roles->pluck('id'))}}"
                                data-route="{{route('role-assign.update', $user->id)}}"
                                data-id="{{$user->id}}"
                                type="button"
                                class="role-assign-button btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#role_update_modal"
                            >
                                <i class="fa-solid fa-repeat"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$users"/>


            <div class="modal fade" id="role_update_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header py-2">
                            <h5 class="modal-title " id="exampleModalLabel">Update Roles</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{-- {!! Form::open(['method' => 'put', 'id'=>'role_update_form']) !!} --}}
                        <form method="post" action="{{route('role-assign.update', $user->id)}}" id="role_update_form">
                            @csrf
                            @method('put')
                            <div class="row">
                                @foreach($roles as $key=>$value)
                                    <div class="col-md-6">
                                        <div class="checkbox-wrapper-19 justify-content-start">
                                            <input
                                                type="checkbox"
                                                name="role_id[]"
                                                {{--                                @if($existing_permissions->where('permission_id', $permission->id)->where('role_id', $role->id)->first())--}}
                                                {{--                                    checked--}}
                                                {{--                                @endif--}}
                                                value="{{$key}}"
                                                id="role_id_input_{{$key}}"
                                                class="me-2 mb-3 role-update-checkbox"

                                            />
                                            <label for="role_id_input_{{$key}}" class="check-box me-2"></label>
                                            <label class="cursor-pointer" for="role_id_input_{{$key}}">{{$value}}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row justify-content-end mt-4">
                                <div class="col-md-6 text-end">
                                    <button type="button" class="btn btn-danger btn-sm " data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                                </div>
                            </div>
                            {{-- {!! Form::close() !!} --}}
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        $('.role-assign-button').on('click', function () {
            let roles = JSON.parse($(this).attr('data-roles'))
            let route = $(this).attr('data-route')
            console.log(route, roles)
            $('#role_update_form').attr('action', route)
            $('.role-update-checkbox').prop('checked', false)
            for (const role of roles) {
                $(`#role_id_input_${role}`).prop('checked', true)
            }
        })
    </script>
@endpush

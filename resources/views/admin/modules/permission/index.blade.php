@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-4">
        <div class="card-body">
            @include('admin.modules.permission.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th>{{ __('SL') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Associated Role') }}</th>
                    <th>{{ __('Date Time') }}
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($permissions as $permission)
                    <tr>
                        <td>
                            <x-serial :serial="$loop->iteration" :collection="$permissions"/>
                        </td>
                        <td>{{$permission->name}}</td>
                        <td style="max-width: 300px">
                            @forelse($permission->roles as $role)
                                <button
                                    style="background-color: rgb(214, 244, 245)"
                                    class="role-button"><small>{{$role->name}}</small></button>
                            @empty
                                <span class="badge bg-warning">No Role</span>
                            @endforelse
                        </td>
                        <td>
                            <x-date-time :created="$permission->created_at" :updated="$permission->updated_at"/>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="4"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$permissions"/>
        </div>
    </div>

@endsection

@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.role.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">{{ __('SL') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Role Type') }}</th>
                    <th>{{ __('Date Time') }}
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>
                        {{ __('Action') }}
                    </th>
                </tr>
                </thead>
                <tbody>
                @forelse($roles as $role)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$roles"/>
                        </td>
                        <td>{{$role->name}}</td>
                        <td>
                            {{ \App\Models\RoleExtended::ROLE_TYPE_LIST[$role->role_type] ?? 'N/A' }}
                        </td>
                        <td>
                            <x-date-time :created="$role->created_at" :updated="$role->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-edit-button :route="route('role.edit', $role->id)"/>
                                <x-delete-button :route="route('role.destroy', $role->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="4"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$roles"/>
        </div>
    </div>
@endsection

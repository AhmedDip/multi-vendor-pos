@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.package.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Plan')</th>
                    <th>@lang('Tagline')</th>
                    <th>@lang('Quota')</th>
                    <th>@lang('Price')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($packages as $package)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$packages"/>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p class="text">{{$package->plan}}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{$package->tagline}}
                        </td>
                        <td>
                            {{$package->quota}}
                        </td>
                        <td>
                            {{$package->price}}
                        </td>
                        <td>
                            <x-date-time :created="$package->created_at" :updated="$package->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('package.show', $package->id)"/>
                                <x-edit-button :route="route('package.edit', $package->id)"/>
                                <x-delete-button :route="route('package.destroy', $package->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$packages"/>
        </div>
    </div>
@endsection

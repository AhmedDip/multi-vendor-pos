@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.tag.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($tags as $tag)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$tags"/>
                        </td>
                        <td>
                            {{$tag->shop?->name}}
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p class="text">{{$tag->name}}</p>
                                    <p class="text-info">{{$tag->slug}}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <x-date-time :created="$tag->created_at" :updated="$tag->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('tag.show', $tag->id)"/>
                                <x-edit-button :route="route('tag.edit', $tag->id)"/>
                                <x-delete-button :route="route('tag.destroy', $tag->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="5"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$tags"/>
        </div>
    </div>
@endsection

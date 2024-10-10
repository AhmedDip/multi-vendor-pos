@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.category.partials.search')
           
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>id</th>
                    <th>@lang('Name')</th>
                    <th>@lang('Shop Name')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$categories"/>
                        </td>
                        <td>{{ $category->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ get_image($category?->photo?->photo)}}" alt="image"
                                        class="img-thumbnail table-image" style="max-width: 60px;">
                                    <div class="ms-2">
                                        <span>{{ $category->name }}</span>
                                        <p class="mb-0 text-secondary"><small><strong>Slug:</strong>
                                                {{ $category->slug }}</small></p>
                                    </div>
                                </div>
                            </div>
                        </td>
                      
                        <td>
                            <p>{{$category?->shop?->name ?? null}}</p>
                        </td>
                        <td class="text-center">{{number($category->sort_order)}}</td>
                        <td class="text-center">
                            @if($category->status == \App\Models\Category::STATUS_ACTIVE)
                                <x-active :status="$category->status"/>
                            @else
                                <x-inactive :status="$category->status" :title="'Inactive'"/>
                                @lang(\App\Models\Category::STATUS_LIST[$category->status] ?? null)
                            @endif
                        </td>
                        <td>
                            <x-date-time :created="$category->created_at" :updated="$category->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('category.show', $category->id)"/>
                                <x-edit-button :route="route('category.edit', $category->id)"/>
                                <x-delete-button :route="route('category.destroy', $category->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="7"/>
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$categories"/>
        </div>
    </div>
@endsection

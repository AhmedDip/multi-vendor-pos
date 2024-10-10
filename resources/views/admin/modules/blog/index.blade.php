@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.blog.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('SL') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Slug') }}</th>
                        <th>{{ __('Click') }}</th>
                        <th>{{ __('Impression') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date Time') }}
                            <x-tool-tip :title="'C = Created at, U = Updated at'" />
                        </th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td class="text-center">
                                <x-serial :serial="$loop->iteration" :collection="$blogs" />
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ get_image($blog?->photo?->photo) }}" alt="image"
                                        class="img-thumbnail table-image" style="max-width: 60px;">
                                    <span class="ms-2">{{ $blog->title }}</span>
                                </div>
                            </td>
                            <td>{{ $blog->slug }}</td>
                            <td class="text-center">{{ $blog->click }}</td>
                            <td class="text-center">{{ $blog->impression }}</td>
                            <td class="text-center">
                                @if ($blog->status == \App\Models\Blog::STATUS_ACTIVE)
                                    <x-active :status="$blog->status" />
                                @elseif($blog->status == \App\Models\Blog::STATUS_INACTIVE)
                                    <x-inactive :status="$blog->status" :title="'Inactive'" />
                                    {{ \App\Models\Blog::STATUS_LIST[$blog->status] ?? null }}
                                @else
                                    <x-active :status="$blog->status" :title="\App\Models\Blog::STATUS_LIST[$blog->status] ?? null" />
                                    {{ \App\Models\Blog::STATUS_LIST[$blog->status] ?? null }}
                                @endif
                            </td>

                            <td>
                                <x-date-time :created="$blog->created_at" :updated="$blog->updated_at" />
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <x-view-button :route="route('blog.show', $blog->id)" />
                                    <x-edit-button :route="route('blog.edit', $blog->id)" />
                                    <x-delete-button :route="route('blog.destroy', $blog->id)" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <x-data-not-found :colspan="9" />
                    @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$blogs" />
        </div>
    </div>
@endsection

@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-5">
        <div class="card-body">
            @include('admin.modules.sales-executive.partials.search')
            <table class="table table-sm table-striped table-hover table-bordered ">
                <thead>
                    <tr>
                        <th class="text-center">{{ __('SL') }}</th>
                        <th>
                            {{ __('Shop Name') }}
                        </th>

                        <th>
                            {{ __('Roles') }}
                        </th>

                        <th>{{ __('Name') }}</th>
                        <th>
                            {{ __('Image') }}
                        </th>
                        <th>{{ __('Email / Phone') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date Time') }}
                            <x-tool-tip :title="'C = Created at, U = Updated at'" />
                        </th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales_executives as $sales_executive)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                <ul>
                                    @foreach ($sales_executive->shops as $shop)
                                        <li class="ms-4">
                                            <a href="{{ route('shop.show', $shop->id) }}">
                                                {{ $shop->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    @foreach ($sales_executive->roles as $role)
                                        <li class="ms-4">{{ $role->name }}</li>
                                    @endforeach
                                </ll>
                            </td>
                            <td>{{ $sales_executive?->name }}</td>
                            <td>
                                <img src="{{ get_image($sales_executive?->photo?->photo) }}" alt="image" class="img-thumbnail table-image" style="max-width: 60px;">
                            </td>
                            <td>{{ $sales_executive?->email }}
                                <small class="text-muted">
                                    <br>
                                    {{ $sales_executive?->phone }}
                                </small>
                            </td>
                            <td class="text-center">
                                @if ($sales_executive->status == \App\Models\User::STATUS_ACTIVE)
                                    <x-active />
                                @else
                                    <x-inactive />
                                @endif
                            </td>
                            <td>
                                <x-date-time :created="$sales_executive->created_at" :updated="$sales_executive->updated_at" />
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <x-view-button :route="route('sales-executive.show', $sales_executive->id)" />
                                    <x-edit-button :route="route('sales-executive.edit', $sales_executive->id)" />
                                    <x-delete-button :route="route('sales-executive.destroy', $sales_executive->id)" />
                                </div>
                            </td>
                        @empty
                            <x-data-not-found :colspan="10" />
                    @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$sales_executives" />
        </div>
    </div>
@endsection

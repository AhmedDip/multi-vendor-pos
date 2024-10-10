@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 text-end">
                    <button class="btn btn-success mb-4 ms-auto me-0" id="create-section-button">
                        <i class="fa-solid fa-plus"></i> Add new address
                    </button>
                </div>
            </div>
            <div class="create-section mb-4" id="create-section" style="display: {{$errors->any() ? 'block' : 'none'}}">
                {!! Form::open(['route' => 'address.store', 'method' => 'post', 'id' => 'create_form']) !!}
                @include('admin.modules.admin-profile.address.partials.form')
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <x-submit-button :type="'create'"/>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="row">
                @forelse($addresses as $address)
                    <div class="col-md-6 mb-4">
                        <div class="card mb-0 custom-card custom-shadow {{$address->is_default ? 'active-card' : ''}}">
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover mb-0">
                                    <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $address->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $address->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $address->address }},
                                            {{ $address?->thana?->name }},
                                            {{ $address?->district?->name }},
                                            {{ $address?->division?->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Landmark</th>
                                        <td>{{ $address->landmark }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($address->status == \App\Models\Address::STATUS_ACTIVE)
                                                <x-active :status="$address->status"/>
                                            @else
                                                <x-inactive :status="$address->status" :title="'Inactive'"/>
                                                {{\App\Models\Address::STATUS_LIST[$address->status] ?? null}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Address Type</th>
                                        <td>{{\App\Models\Address::ADDRESS_TYPE_LIST[$address->address_type] ?? null}}</td>
                                    </tr>
                                    <tr>
                                        <th>Date Time</th>
                                        <td>
                                            <x-date-time :created="$address->created_at" :updated="$address->updated_at"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td>
                                            <div class="d-flex">
                                                <x-view-button :route="route('address.show', $address->id)"/>
                                                <x-edit-button :route="route('address.edit', $address->id).'?redirect='.route('profile.address')"/>
                                                <x-delete-button :route="route('address.destroy', $address->id)"/>
                                                @if(!$address->is_default)
                                                    {!! Form::open(['route' => ['my-address.make-default', $address->id], 'method' => 'put', 'id' => 'make-default-form']) !!}
                                                    <button
                                                        class="btn btn-success btn-sm ms-1 delete_swal"
                                                        data-title="Are you sure to make default?"
                                                        data-text="All others address will remove default status"
                                                        data-confirm_text="Yes, Make Default"
                                                        data-target_form_id="#make-default-form"
                                                        type="button"
                                                    >
                                                        Make Default
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-4">
                        <div class="card custom-shadow">
                            <div class="card-body">
                                <p class="text-danger">No address found</p>
                            </div>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $('#create-section-button').on('click', function () {
            $('#create-section').slideToggle();
        })
    </script>
@endpush

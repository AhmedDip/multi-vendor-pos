@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.appointment.partials.search')
            
            <div class="row">
                <div class="col-md-3">
                    {{-- <div class="mb-2 d-flex justify-content-start">
                        <button id="downloadPdf" class="btn btn-primary btn-sm">{{ __('Selected Invoice') }}</button>
                    </div> --}}
                </div>
                <div class="col-md-9">
                    <div class="mb-2 d-flex justify-content-end">
                        <a href="{{ route('appointment.export-pdf') }}" class="btn btn-danger btn-sm me-2" type="submit">
                            <i class="fa-solid fa-download"></i> @lang('Export PDF')
                        </a>
                        <a href="{{ route('appointment.export') }}" class="btn btn-primary btn-sm" type="submit">
                            <i class="fa-solid fa-download"></i> @lang('Export CSV')
                        </a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    {{-- <th class="text-center"><input type="checkbox" id="selectAll"></th> --}}
                    <th class="text-center">@lang('SL')</th>
                    {{-- <th class="text-center">{{ __('Invoice No') }}</th> --}}
                    <th>@lang('Customer')</th>
                    <th>@lang('Shop')</th>
                    <th>@lang('Category')</th>
                    <th>@lang('Product')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                    <th>@lang('Action')</th>
                    
                </tr>
                </thead>
                <tbody>
                @forelse($appointments as $appointment)
                    <tr>
                        {{-- <td class="text-center">
                            <input type="checkbox" class="appointment-checkbox" value="{{ $appointment->id }}">
                        </td> --}}
                        <td class="text-center">
                           <x-serial :serial="$loop->iteration" :collection="$appointments"/>
                        </td>
                        <td class="text-center">
                            <p class="text-success"><small>{{$appointment?->invoice_number}}</small></p>
                        </td>
                        <td style="font-size: 12px;"> 
                            <div class="d-flex align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-user"></i>
                                    <div class="ms-2">
                                        <span>{{ $appointment->name }}</span>
                                        <p class="mb-0 text-secondary"><small><strong>Phone:</strong>
                                                {{ $appointment->phone }}</small></p>
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="text-center">
                            <p>{{$appointment?->get_shop?->name}}</p>
                        </td>
                       
                        <td class="text-center">
                            <p>{{$appointment?->get_category?->name}}</p>
                        </td>
                        <td class="text-center">
                                <ul class="list-unstyled">
                                    @foreach ( $appointment->services as $service)
                                        <li style="display: flex;align-items: center;">
                                            <i class="fa-solid fa-circle-check text-success" style="font-size:4px;margin-right:2px;"></i>{{$service->name}}
                                        </li>
                                    @endforeach
                                </ul>
                        </td>
                        
                        <td>
                            <p class="text-success">{{ \Carbon\Carbon::parse($appointment->date)->format('l, d F, Y h:i a') }}</p>
                        </td>
                        <td>
                            <x-date-time :created="$appointment->created_at" :updated="$appointment->updated_at"/>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('appointment.show', $appointment->id)"/>
                                <x-edit-button :route="route('appointment.edit', $appointment->id)"/>
                                <x-delete-button :route="route('appointment.destroy', $appointment->id)"/>
                                <x-pay-now-button :route="route('order.create', ['shop_id' => $appointment->shop_id, 'customer_name' => $appointment->name, 'customer_phone' => $appointment->phone, 'invoice_number' => $appointment->invoice_number, 'appointment_id' => $appointment->id])"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="12"/> 
                @endforelse
                </tbody>
            
            </table>
            <x-pagination :collection="$appointments"/>
        </div>
    </div>
    {{-- modal --}}
    <div class="modal fade" id= "selectedAppointmentsModal" tabindex="-1" aria-labelledby="selectedAppointmentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectedOrdersModalLabel">{{ __('Selected Orders')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <th>{{ __('Shop Name') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Customer') }}</th>
                            <th>{{ __('Total Amount') }}</th>
                        </thead>
                        <tbody id="selectedAppointmentsBody">
                           {{-- go to js --}}
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close')}}</button>
                    <button type="button" class="btn btn-primary" id="downloadSelectedPdf"><i class="fa-solid fa-download"></i> {{ __('Download Invoice')}}</button>
                </div>
            </div>
        </div>
    </div>
   
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const appointmentCheckboxes = document.querySelectorAll('.appointment-checkbox');
        const downloadPdfButton = document.getElementById('downloadPdf');
        const selectedAppointmentsModal = new bootstrap.Modal(document.getElementById('selectedAppointmentsModal'));
        const selectedAppointmentsBody = document.getElementById('selectedAppointmentsBody');
        const downloadSelectedPdfButton = document.getElementById('downloadSelectedPdf');

        selectAllCheckbox.addEventListener('change', function() {
            appointmentCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        downloadPdfButton.addEventListener('click', function() {
            const selectedAppointmentIds = Array.from(appointmentCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedAppointmentIds.length > 0) {
                fetchSelectedAppointments(selectedAppointmentIds);
            } else {
                fetchSelectedAppointments([]);
            }

            selectedAppointmentsModal.show();
        });

        downloadSelectedPdfButton.addEventListener('click', function() {
            const selectedAppointmentIds = Array.from(appointmentCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedAppointmentIds.length > 0) {
                window.location.href = "{{ route('appointment.download-appointment-invoice') }}?appointments=" + selectedAppointmentIds.join(',');
            } else {
                window.location.href = "{{ route('appointment.download-appointment-invoice') }}";
            }
        });

        function fetchSelectedAppointments(appointmentIds) {
            fetch("{{ route('appointment.getAppointmentsInvoiceForDownloadPdf') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ appointment_ids: appointmentIds })
            })
            .then(response => response.json())
            .then(data => {
                selectedAppointmentsBody.innerHTML = '';
                data.appointments.forEach(appointment => {
                    const row = `<tr>
                        <td>${appointment.shop_name}</td>
                        <td>${appointment.date}</td>
                        <td>${appointment.name}</td>
                        <td>${appointment.total_payable_amount}</td>
                    </tr>`;
                    selectedAppointmentsBody.insertAdjacentHTML('beforeend', row);
                });
            });
        }
    });
    
</script>
@endpush


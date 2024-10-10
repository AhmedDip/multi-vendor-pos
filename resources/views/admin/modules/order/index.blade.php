@extends('admin.layouts.app')
@section('content')

    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.order.partials.dashboard')
            @include('admin.modules.order.partials.search')

            <div class="row">
                <div class="col-md-3">
                    <div class="mb-2 d-flex justify-content-start">
                        <button id="downloadPdf" class="btn btn-primary btn-sm">{{ __('Selected Invoice') }}</button>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="mb-2 d-flex justify-content-end">
                        <a href="{{ route('order.export-pdf') }}" class="btn btn-danger btn-sm me-2" type="submit">
                            <i class="fa-solid fa-download"></i> @lang('Export PDF')
                        </a>
                        <a href="{{ route('order.export') }}" class="btn btn-primary btn-sm" type="submit">
                            <i class="fa-solid fa-download"></i> @lang('Export CSV')
                        </a>
                    </div>
                </div>
            </div>

            <div class="mb-2 d-flex justify-content-start">

            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"><input type="checkbox" id="selectAll"></th>
                            {{-- <th>{{ __('Id') }}</th> --}}
                            <th class="text-center">{{ __('Invoice No') }}</th>
                            <th>{{ __('Shop Name') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>
                                {{ __('Customer') }}
                            </th>
                            <th>
                                {{ __('Ordered Product') }}
                            </th>
                            <th>
                                {{ __('Total Payable Amount') }}
                            </th>

                            <th>
                                {{ __('Paid Amount') }}
                            </th>

                            <th>
                                {{ __('Order Status') }}
                            </th>

                            <th>
                                {{ __('Payment Status') }}
                            </th>
                            <th>{{ __('Date Time') }}
                                <x-tool-tip :title="'C = Created at, U = Updated at'" />
                            </th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                </td>
                                {{-- <td>{{ $order->id }}</td> --}}
                                <td class="text-center text-success"><small>{{ $order->invoice_number }}</small></a>
                                </td>

                                <td>{{ $order?->shop?->name }}</td>

                                <td class="text-success">{{ \Carbon\Carbon::parse($order->order_date)->format('d F, Y') }}
                                </td>

                                <td style="font-size: 12px;">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-user"></i>
                                            <div class="ms-2">
                                                <span>{{ $order?->customer?->name }}</span>
                                                <p class="mb-0 text-secondary"><small><strong>Phone:</strong>
                                                        {{ $order?->customer?->phone }}</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td style="font-size: 11px;">
                                    <ol>
                                        @foreach ($order?->orderDetails as $orderDetail)
                                            <li class="ms-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="d-flex align-items-center">
                                                        <div class="ms-2">
                                                            <span>{{ $orderDetail?->product?->name }}</span>
                                                            <p class="mb-0 text-secondary"><small><strong>X</strong>
                                                                    {{ $orderDetail?->quantity }}</small></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ol>
                                </td>


                                <td>{{ $order?->total_payable_amount }} ৳</td>

                                <td>
                                    {{ $order->total_paid_amount }} ৳
                                </td>

                                <td>
                                    <span class="badge bg-dark">
                                        {{ \App\Models\Order::STATUS_LIST[$order?->status] ?? 'Pending' }}
                                    </span>
                                </td>

                                <td>
                                    @if ($order->total_payable_amount == $order->total_paid_amount)
                                        <span class="badge bg-success">
                                            {{ __('Paid') }}
                                        </span>
                                    @elseif($order->total_paid_amount == 0)
                                        <span class="badge bg-danger">
                                            {{ __('Unpaid') }}
                                        </span>
                                    @elseif ($order->total_payable_amount > $order->total_paid_amount)
                                        <span class="badge bg-warning">
                                            {{ __('Partial') }}
                                        </span>
                                    @else
                                        <span class="badge bg-info">
                                            {{ __('Overpaid') }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <x-date-time :created="$order->created_at" :updated="$order->updated_at" />
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <x-view-button :route="route('order.show', $order->id)" />
                                        @if ($order->status !== \App\Models\Order::STATUS_CANCELED)
                                            <x-edit-button :route="route('order.edit', $order->id)" />
                                        @endif
                                        <x-delete-button :route="route('order.destroy', $order->id)" />
                                    </div>
                                </td>
                            @empty
                                <x-data-not-found :colspan="12" />
                        @endforelse
                    </tbody>
                </table>
                <x-pagination :collection="$orders" />
            </div>
        </div>
    </div>


    {{-- modal --}}
    <div class="modal fade" id= "selectedOrdersModal" tabindex="-1" aria-labelledby="selectedOrdersModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectedOrdersModalLabel">{{ __('Selected Orders') }}</h5>
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
                        <tbody id="selectedOrdersBody">
                            {{-- go to js --}}
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="downloadSelectedPdf"><i
                            class="fa-solid fa-download"></i> {{ __('Download Invoice') }}</button>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const orderCheckboxes = document.querySelectorAll('.order-checkbox');
            const downloadPdfButton = document.getElementById('downloadPdf');
            const selectedOrdersModal = new bootstrap.Modal(document.getElementById('selectedOrdersModal'));
            const selectedOrdersBody = document.getElementById('selectedOrdersBody');
            const downloadSelectedPdfButton = document.getElementById('downloadSelectedPdf');

            selectAllCheckbox.addEventListener('change', function() {
                orderCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            downloadPdfButton.addEventListener('click', function() {
                const selectedOrderIds = Array.from(orderCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedOrderIds.length > 0) {
                    fetchSelectedOrders(selectedOrderIds);
                } else {
                    fetchSelectedOrders([]);
                }

                selectedOrdersModal.show();
            });

            downloadSelectedPdfButton.addEventListener('click', function() {
                const selectedOrderIds = Array.from(orderCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                if (selectedOrderIds.length > 0) {
                    window.location.href = "{{ route('order.download-invoice-pdf') }}?orders=" +
                        selectedOrderIds.join(',');
                } else {
                    window.location.href = "{{ route('order.download-invoice-pdf') }}";
                }
            });

            function fetchSelectedOrders(orderIds) {
                fetch("{{ route('order.getOrdersInvoiceForDownloadPdf') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            order_ids: orderIds
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        selectedOrdersBody.innerHTML = '';
                        data.orders.forEach(order => {
                            const row = `<tr>
                        <td>${order.shop_name}</td>
                        <td>${order.order_date}</td>
                        <td>${order.name}</td>
                        <td>${order.total_amount}</td>
                    </tr>`;
                            selectedOrdersBody.insertAdjacentHTML('beforeend', row);
                        });
                    });
            }
        });
    </script>
@endpush

@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            @include('admin.modules.expense.partials.search')
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    <th class="text-center">@lang('SL')</th>
                    <th>@lang('Purpose')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Shop Name')</th>
                    <th>@lang('Created By')</th>
                    <th>@lang('Date')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td class="text-center">
                           <x-serial :serial="$loop->iteration" :collection="$expenses"/>
                        </td>
                        <td>
                            <div class="d-flex justify-items-center">
                                <div class="ms-2">
                                    <p>{{$expense->purpose}}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <p>{{$expense->amount}}</p>
                        </td>
                        <td class="text-center">
                            <p>{{$expense?->get_shop?->name}}</p>
                        </td>
                        <td class="text-center">
                            {{-- <p>{{$expense?->get_user->name}}</p> --}}
                            {{$expense?->created_by?->name}}
                        </td>
                        <td>
                            <p class="text-success">{{ \Carbon\Carbon::parse($expense->date)->format('l, d F, Y h:i a') }}</p>
                        </td>
                    
                        <td>
                            <div class="d-flex justify-content-center">
                                <x-view-button :route="route('expense.show', $expense->id)"/>
                                <x-edit-button :route="route('expense.edit', $expense->id)"/>
                                <x-delete-button :route="route('expense.destroy', $expense->id)"/>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-data-not-found :colspan="12"/> 
                @endforelse
                </tbody>
            </table>
            <x-pagination :collection="$expenses"/>
        </div>
    </div>
@endsection

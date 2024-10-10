@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th>@lang('ID')</th>
                            <td>{{$expense->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Purpose')</th>
                            <td>{{$expense->purpose}}</td>
                        </tr>
                        <tr>
                            <th>@lang('amount')</th>
                            <td>{{$expense->amount}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Date')</th>
                            <td class="text-success">{{ \Carbon\Carbon::parse($expense->date)->format('l, d F, Y h:i a') }}</td>
                        </tr>
                        <tr>
                            <th>@lang('Shop Name')</th>
                            <td>{{$expense?->get_shop?->name}}</td>
                        </tr>
                       
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$expense?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$expense->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$expense->created_at"/>
                                <small class="text-success">{{$expense->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$expense->created_at" :updated="$expense->updated_at"/>
                                <small class="text-success">{{$expense->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 col-md-12">
                    <x-activity-log :logs="$expense->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

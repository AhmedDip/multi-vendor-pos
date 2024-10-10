@extends('admin.layouts.app')
@section('content')
    <div class="pt-5 card body-card">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <table class="table table-striped table-hover table-bordered">
                        <tbody>
                        <tr>
                            <th colspan="12" class="text-center">Service Details</th>
                        </tr>
                        <tr>
                            <th>@lang('For Shop')</th>
                            <td>{{$appointment?->get_shop?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Category')</th>
                            <td>{{$appointment?->get_category?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Services')</th>
                            
                            <td>  
                                <ul class="list-unstyled">
                                    @foreach ( $appointment->services as $service)
                                        <li style="display: flex;align-items: center;">
                                            <i class="fa-solid fa-circle-dot text-success" style="font-size:5px;margin-right:5px;"></i>{{$service->name}}
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                    
                
                        </tr>
                        <tr>
                            <th>@lang('Date')</th>
                            <td class="text-success">{{ \Carbon\Carbon::parse($appointment->date)->format('l, d F, Y h:i a') }}</td>
                        </tr>
                        <tr>
                            <th colspan="12" class="text-center">Customer Details</th>
                        </tr>
                        <tr>
                            <th>@lang('ID')</th>
                            <td>{{$appointment->id}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Customer Name')</th>
                            <td>{{$appointment->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Email')</th>
                            <td>{{$appointment->email}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Phone')</th>
                            <td>{{$appointment->phone}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Message')</th>
                            <td>{{$appointment->message}}</td>
                        </tr>
                        
                        <tr>
                            <th>@lang('Created By')</th>
                            <td>{{$appointment?->created_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Updated By')</th>
                            <td>{{$appointment->updated_by?->name}}</td>
                        </tr>
                        <tr>
                            <th>@lang('Created at')</th>
                            <td>
                                <x-created-at :created="$appointment->created_at"/>
                                <small class="text-success">{{$appointment->created_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('Updated at')</th>
                            <td>
                                <x-updated-at :created="$appointment->created_at" :updated="$appointment->updated_at"/>
                                <small class="text-success">{{$appointment->updated_at->diffForHumans()}}</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 col-md-12">
                    <x-activity-log :logs="$appointment->activity_logs"/>
                </div>
            </div>

        </div>
    </div>
@endsection

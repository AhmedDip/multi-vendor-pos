<div class="col-md-12 mt-4">
    <fieldset>
        <legend>Activity Log</legend>
        <table class="table table-striped table-hover table-bordered mt-3" style="font-size: 13px">
            <thead>
            <tr>
                <th>SL</th>
                <th>User</th>
                <th>Note</th>
                <th>Client Details</th>
                <th>Old data</th>
                <th>New data</th>
                <th>Date Time</th>
            </tr>
            </thead>
            @isset($logs)
                @forelse($logs as $activity_log)
                    <tbody>
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>
                            @if($activity_log->creatable_type == 'App\Models\User')
                                <span class="text-primary fw-bold">Admin <i class="mdi mdi-arrow-right-bold-outline text-success"></i></span>
                            @else
                                <span class="text-primary fw-bold">Auth User <i class="mdi mdi-arrow-right-bold-outline text-success"></i></span>
                            @endif
                            {{$activity_log?->creatable?->name}}
                        </td>
                        <td class="text-danger">{{$activity_log?->note}}</td>
                        <td style="max-width: 200px">
                            <p><small><span class="text-success">IP</span>: {{$activity_log?->ip}}</small></p>
                            <p>
                                <small>
                                    <span class="text-success">Method Action</span>:
                                    {{$activity_log?->method}}
                                </small>
                            </p>
                            <p><small><span class="text-success">Route</span>: {{$activity_log?->route}}</small></p>
                        </td>
                        <td style="max-width: 200px">
                            @php
                                $old_log_data = json_decode($activity_log?->old_data, true);
                                $old_log_data= !empty($old_log_data) ? $old_log_data : []
                            @endphp
                            @forelse($old_log_data as $key=>$value)
                                <p>
                                    <strong class="text-success">
                                        {{ucwords(str_replace('_', ' ', $key))}}
                                    </strong>
                                    : {{$value}}
                                </p>
                            @empty
                                <p class="text-danger">No data</p>
                            @endforelse
                        </td>
                        <td style="max-width: 200px">
                            @php
                                $log_data = json_decode($activity_log?->new_data, true);
                                $log_data= !empty($log_data) ? $log_data : []
                            @endphp
                            @forelse($log_data as $key=>$value)
                                @if($key =='created_at' || $key =='updated_at')
                                    @continue
                                @endif
                                <p>
                                    <strong class="text-success">{{ucwords(str_replace('_', ' ', $key))}}</strong>
                                    : @php print_r($value) @endphp
                                </p>
                            @empty
                                <p class="text-danger">No data</p>
                            @endforelse
                        </td>
                        <td>
                            {{$activity_log?->created_at->toDayDatetimeString()}}
                            <p class="text-success"><small>{{$activity_log?->created_at->diffForHumans()}}</small></p>
                        </td>
                    </tr>
                    </tbody>
                @empty
                    <tr>
                        <td colspan="8"><p class="text-center text-danger">No data found</p></td>
                    </tr>
                @endforelse
            @endisset
        </table>
    </fieldset>
</div>

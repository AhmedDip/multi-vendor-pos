<p class="text-success">
    {{date_time($created)}}
</p>
<p class="text-theme">{{$created != $updated ? date_time($updated) : __('Not updated')}}</p>

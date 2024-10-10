<div {{$attributes->merge(['class'=>'d-grid'])}}>
    @if($type=='create')
        <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-plus"></i> Add</button>
    @else
        <button type="submit" class="btn btn-sm btn-success"><i class="fa-solid fa-edit"></i> Update</button>
    @endif
</div>

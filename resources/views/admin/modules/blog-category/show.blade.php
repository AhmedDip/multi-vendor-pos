@extends('admin.layouts.app')
@section('content')

<div class="card body-card pt-5 mb-4">
    <div class="card-body">
    

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab"
                tabindex="0">
                <div class="tab-pane-custom" id="nav-basic">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <table class="table table-striped table-hover table-bordered">
                                <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{$blog_category->id}}</td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{$blog_category->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td>{{$blog_category->slug}}</td>
                                    </tr>
                                    @if($blog_category->parent_id)
                                    <tr>
                                        <th>Parent Category</th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <table class="table">
                                                    <tr>
                                                        <th>ID</th>
                                                        <td>{{$blog_category->parent?->id}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Name</th>
                                                        <td>{{$blog_category->parent?->name}}</td>
                                                    </tr>
                                                </table>
                                            </div>
            
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>Photo</th>
                                        <td class="d-flex justify-content-center">
                                            <a href="{{\Illuminate\Support\Facades\Storage::url($blog_category->photo?->photo)}}">
                                                <img src="{{\Illuminate\Support\Facades\Storage::url($blog_category->photo?->photo)}}" alt="{{$blog_category->name}}"
                                                class="img-fluid shadow-sm rounded border" style="max-width: 100px;">
                                            </a>
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($blog_category->status == \App\Models\BlogCategory::STATUS_ACTIVE)
                                            <x-active :status="$blog_category->status" />
                                            @else
                                            <x-inactive :status="$blog_category->status" :title="'Inactive'" />
                                            {{\App\Models\BlogCategory::STATUS_LIST[$blog_category->status] ?? null}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{!! $blog_category->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td>{{$blog_category?->created_by?->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated By</th>
                                        <td>{{$blog_category?->updated_by?->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Created at</th>
                                        <td>
                                            <x-created-at :created="$blog_category->created_at" />
                                            <small class="text-success">{{$blog_category->created_at->diffForHumans()}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Updated at</th>
                                        <td>
                                            <x-updated-at :created="$blog_category->created_at" :updated="$blog_category->updated_at" />
                                            <small class="text-success">{{$blog_category->updated_at->diffForHumans()}}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-4">
                            <x-activity-log :logs="$blog_category->activity_logs" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
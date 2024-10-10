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
                                        <td>{{$blog->id}}</td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td>{{$blog->title}}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td>{{$blog->slug}}</td>
                                    </tr>
                                    <tr>
                                        <th>Categories</th>
                                        <td>
                                            @foreach($blog->categories as $category)
                                            <span class="badge bg-primary">{{$category->name}}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <th>Click</th>
                                        <td>{{$blog->click}}</td>
                                    </tr>
                                    <tr>
                                        <th>Impression</th>
                                        <td>{{$blog->impression}}</td>
                                    </tr>
                                    <tr>
                                        <th>Photo</th>
                                        <td class="d-flex justify-content-center">
                                            <a href="{{\Illuminate\Support\Facades\Storage::url($blog->photo?->photo)}}">
                                                <img src="{{\Illuminate\Support\Facades\Storage::url($blog->photo?->photo)}}" alt="{{$blog->name}}"
                                                class="img-fluid shadow-sm rounded border" style="max-width: 100px;">
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            @if($blog->status == \App\Models\Blog::STATUS_ACTIVE)
                                            <x-active :status="$blog->status" />
                                            {{\App\Models\Blog::STATUS_LIST[$blog->status] ?? null}}
                                            @elseif($blog->status == \App\Models\Blog::STATUS_INACTIVE)
                                            <x-inactive :status="$blog->status" :title="'Inactive'" />
                                            {{\App\Models\Blog::STATUS_LIST[$blog->status] ?? null}}
                                            @else
                                            <x-active :status="$blog->status" :title="\App\Models\Blog::STATUS_LIST[$blog->status] ?? null" />
                                            {{\App\Models\Blog::STATUS_LIST[$blog->status] ?? null}}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Is Comment Allowed</th>
                                        <td>
                                            @if($blog->is_comment_allowed)
                                            <x-active :title="'Yes'" />
                                            Yes
                                            @else
                                            <x-inactive :title="'No'" />
                                            No
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Start Date</th>
                                        <td>{{\Carbon\Carbon::parse($blog->start_date)->format('d M, Y h:i A')}}</td>
                                    </tr>
                                    <tr>
                                        <th>End Date</th>
                                        <td>{{\Carbon\Carbon::parse($blog->end_date)->format('d M, Y h:i A')}}</td>
                                    </tr>
                                    <tr>
                                        <th>Tag</th>
                                        @php $tags = explode(',', $blog->tag); @endphp
                                        <td>
                                            @foreach($tags as $tag)
                                            <span class="badge bg-primary">{{$tag}}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Summary</th>
                                        <td>{{$blog->summary}}</td>
                                    </tr>
                                    <tr>
                                        <th>Content</th>
                                        <td>{!! Str::limit($blog->content, 1000, '...') !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Created By</th>
                                        <td>{{$blog?->created_by?->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Updated By</th>
                                        <td>{{$blog?->updated_by?->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>Created at</th>
                                        <td>
                                            <x-created-at :created="$blog->created_at" />
                                            <small class="text-success">{{$blog->created_at->diffForHumans()}}</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Updated at</th>
                                        <td>
                                            <x-updated-at :created="$blog->created_at" :updated="$blog->updated_at" />
                                            <small class="text-success">{{$blog->updated_at->diffForHumans()}}</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12 mt-4">
                            <x-activity-log :logs="$blog->activity_logs" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
                tabindex="0">
                <div class="tab-pane-custom" id="nav-seo">
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <table class="table table-striped table-hover table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Meta Title</th>
                                        <td>{{$blog->seo?->title}}</td>
                                    </tr>
                                    <tr>
                                        <th>Meta Description</th>
                                        <td>{{$blog->seo?->description}}</td>
                                    </tr>
                                    <tr>
                                        <th>Meta Keywords</th>
                                        @php $keywords = explode(',', $blog->seo?->keywords); @endphp
                                        <td>
                                            @foreach($keywords as $keyword)
                                            <span class="badge bg-primary">{{$keyword}}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Og Image</th>
                                        <td class="d-flex justify-content-center">
                                            <a href="{{\Illuminate\Support\Facades\Storage::url($blog->seo?->photo?->photo)}}">
                                                <img src="{{\Illuminate\Support\Facades\Storage::url($blog->seo?->photo?->photo)}}" alt="{{$blog->name}}"
                                                class="img-fluid shadow-sm rounded border" style="max-width: 100px;">
                                            </a>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane-custom" id="nav-preview">
                    <div class="col-md-12 mt-4">
                        <div class="border p-4 m-2">
                            {!! $blog->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
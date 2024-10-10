
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="{{ public_path('admin_assets/css/media_library.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ public_path('theme/css/common-css.css') }}" rel="stylesheet" type="text/css"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>{{ $title }}</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #EEEEEE;
            padding: 8px;
            text-align: left;
        }
        td {
            font-size: 13px;
        }
        th {
            font-size: 14px;
            background-color: #f2f2f2;
        }
        .name-style{
            font-size: 16px;
            margin-top:10px;
        }
        .title-divider {
            display: block;
            max-width: 80px;
            height: 2px;
            background: rgba(233, 102, 49, 1);
            margin: 15px auto;
        }
        .name-style strong{
            font-size: 14px;
        }
        .name-style p{
            margin-top:6px;
        }
    </style>
</head>
<body>
    
    
    <h1 class="text-center" style="color:#555555">{{ $title }}</h1>
    <div class="title-divider"></div>
    
    
    <div class="pt-5 card body-card">
        <div class="card-body">
           
            <table class="table table-striped table-hover table-bordered ">
                <thead>
                <tr>
                    
                    <th class="text-center">@lang('SL')</th>
                    {{-- <th class="text-center">@lang('id')</th> --}}
                    <th>@lang('Category Details')</th>
                    {{-- <th>@lang('Slug')</th> --}}
                    {{-- <th>@lang('Description')</th> --}}
                    <th>@lang('Shop Name')</th>
                    <th>@lang('Sort Order')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Date Time')
                        <x-tool-tip :title="'C = Created at, U = Updated at'"/>
                    </th>
                </tr>
                </thead>
                <tbody>
                    
                @foreach($categories as $category)
                    <tr>
                        <td class="text-center">
                            <x-serial :serial="$loop->iteration" :collection="$categories"/>
                        </td>
                        {{-- <td>
                            <p>{{$category->id}}</p>
                        </td> --}}
                        <td class="text-center">
                            <div class="d-flex justify-items-center" style="display:flex;gap:2px;flex-wrap: wrap;">
                               <div>
                                @if($category->photo && $category->photo->photo)
                                    @php
                                        $imageUrl = Storage::url($category->photo->photo);
                                    @endphp
                                    <img src="{{ public_path($imageUrl) }}" alt="image" class="img-thumbnail table-image" style="max-width: 40px;">
                                @endif
                               </div>
                                <div class="name-style">
                                    <p>{{$category->name}}</p>
                                    <p class="mb-0 text-secondary"><small><strong>Slug:</strong> {{ $category->slug }}</small></p>
                                    
                                    @if($category->description)
                                        <p class="mb-0 text-secondary"><small><strong>Description:</strong> {{ $category->description }}</small></p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        {{-- <td>
                            <p>{{$category->slug}}</p>
                        </td> --}}
                        {{-- <td>
                            <p>{{$category->description}}</p>
                        </td> --}}
                        <td class="text-center">
                            <p>{{$category?->shop?->name ?? null}}</p>
                        </td>
                        <td class="text-center">{{$category->sort_order}}</td>
                        <td>{{ $category->status == 1 ? 'Active' : 'Inactive' }}</td>
                       
                        <td>
                            <x-date-time :created="$category->created_at" :updated="$category->updated_at"/>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

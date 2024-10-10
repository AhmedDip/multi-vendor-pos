@extends('admin.layouts.app')
@section('content')
    <div class="card body-card pt-4">
        <div class="card-body">
            {{ html()->form('POST', route('permission.store').'?redirect='.route('role-permission-association.index'))->open() }}
            <button class="btn btn-success btn-sm mb-4"><i class="fa-solid fa-repeat"></i> Re-Generate permissions</button>
            {{ html()->form()->close() }}
            @include('admin.modules.permission.partials.search')
            {{-- {!! Form::open(['route' => 'role-permission-association.store', 'method' => 'post']) !!} --}}
            {{ html()->form('POST', route('role-permission-association.store'))->open() }}
            <table class="table table-hover table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Permission</th>
                    @foreach($roles as $role)
                        <th>
                            {{$role->name}}
                            <div class="checkbox-wrapper-19">
                                <input
                                    type="checkbox"
                                    value="1"
                                    class="all-permission-select-checkbox"
                                    id="checkbox_all_{{$role->id}}"
                                    data-class="checkbox_all_{{strtolower(str_replace(' ', '_', $role->name))}}"
                                />
                                <label for="checkbox_all_{{$role->id}}" class="check-box"></label>
                                <small class="ms-1 fw-normal">Check All</small>
                            </div>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="text-start">{{ucwords(str_replace(['-', '_', '.'], ' ', $permission->name))}} <x-tool-tip :title="$permission->name"/></td>
                        @foreach($roles as $role)
                            <td>
                                <div class="checkbox-wrapper-19">
                                    <div class="d-none">
                                        {{-- {!! Form::checkbox('role_permissions['.$role->id.']['.$permission->id.']', 0, true, ['class'=>'form-check-input']) !!} --}}
                                        <input type="checkbox" name="role_permissions[{{$role->id}}][{{$permission->id}}]" value="0" checked class="form-check-input">
                                    </div>
                                    <input
                                        type="checkbox"
                                        name="role_permissions[{{$role->id}}][{{$permission->id}}]"
                                        @if($existing_permissions->where('permission_id', $permission->id)->where('role_id', $role->id)->first())
                                            checked
                                        @endif
                                        value="1"
                                        id="checkbox_{{$role->id}}_{{$permission->id}}"
                                        class="permission_checkbox checkbox_all_{{strtolower(str_replace(' ', '_', $role->name))}}"
                                    />
                                    <label for="checkbox_{{$role->id}}_{{$permission->id}}" class="check-box"></label>
                                </div>

                            </td>
                        @endforeach
                    </tr>

                @endforeach
                </tbody>

            </table>
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <x-submit-button :type="'update'"/>
                </div>
            </div>
            {{-- {!! Form::close() !!} --}}
            {{ html()->form()->close() }}
        </div>
    </div>
@endsection

@push('scripts')

<script>
    $('.all-permission-select-checkbox').on('change', function () {
      let effected_classes = $(this).attr('data-class')
      if ($(this).is(':checked')) {
          $(`.${effected_classes}`).prop('checked', true)
      } else {
          $(`.${effected_classes}`).prop('checked', false)
      }
  })
  
  
  //permission checkbox checked
  
  const handleAllPermissionCheckBox = () => {
      $('.all-permission-select-checkbox').each(function (index, node) {
          let checked = true
          $(this).each(function (ind, checkbox) {
              let data_class = $(this).attr('data-class')
  
              $(`.${data_class}`).each(function (indx, item) {
                  let is_checked = $(this).is(':checked')
                  if (!is_checked) {
                      checked = false
                  }
              })
          })
          $(this).prop('checked', checked)
      })
  }
  
  handleAllPermissionCheckBox()
  $('.permission_checkbox').on('change', function () {
      handleAllPermissionCheckBox()
  })
  
  
  </script>

@endpush

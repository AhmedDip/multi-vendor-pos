<?php

namespace App\Http\Controllers;

use App\Models\RoleExtended;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class RoleAssignController extends Controller
{
    public static string $route = 'role-assign';

    public function index(Request $request)
    {
        $cms_content = [
            'title'        => 'User Role Assign', //page title, breadcrumb second item
            'title_route'  => route('role-assign.index'), //breadcrumb second route
            'active_title' => 'List', // create, list, edit, update
        ];

        $users   = (new User())->get_admins($request);
        $roles   = (new RoleExtended())->get_roles_assoc();
        $columns = [
            'name'       => trans('Name'),
            'email'      => trans('Email'),
            'phone'      => trans('Phone'),
            'created_at' => trans('Created Time'),
            'updated_at' => trans('Updated Time'),
        ];
        $search  = $request->all();

        return view('admin.modules.role-assign.index', compact(
            'columns',
            'users',
            'cms_content',
            'roles',
            'search'
        ));
    }

    public function edit(User $user)
    {
        $cms_content = [
            'module_name'     => 'User Edit', //page title, breadcrumb second item
            'module_route'    => route('role-assign.index'), //breadcrumb second route
            'sub_module_name' => 'Edit', // create, list, edit, update
            'button_type'     => 'update', //create
            'action_route'    => route('role-assign.update', $user->id),
        ];

        return view('backend.modules.role-assign.edit', compact(
            'cms_content',
            'user'));
    }

    public function update(Request $request, int $user_id)
    {
        try {
            DB::beginTransaction();
            $user = (new User())->get_user_by_id($user_id);

            $user?->roles()->sync(array_values($request->input('role_id', [])));


            DB::commit();
            success_alert('Role assigned successfully');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ROLE_ASSIGN_FAILED', $throwable, 'error');
            success_alert($throwable->getMessage());
        }
        return redirect()->back();
    }
}

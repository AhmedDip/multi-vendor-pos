<?php

namespace App\Http\Controllers;

use App\Models\PermissionExtended;
use App\Models\RoleExtended;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RolePermissionAssociationController extends Controller
{
    public static string $route = 'role-permission-association';

    final public function index(Request $request): View
    {
        $cms_content = [
            'module_name'  => 'Role permission association',
            'title_route'  => route('role-permission-association.index'),
            'active_title' => 'List',
            'button_type'  => 'create',
            'button_text'  => 'Regenerate permission',
            'button_route' => route('role-permission-association.store'),
        ];
        $roles       = (new RoleExtended())->get_roles($request, true, true);
        $permissions = (new PermissionExtended())->get_permissions($request, true);

        $existing_permissions = DB::table('role_has_permissions')->get();
        $columns              = [
            'name'       => 'Name',
            'created_at' => 'Created Time',
            'updated_at' => 'Updated Time',
        ];
        $search               = $request->all();

        return view(
            'admin.modules.role-permission-association.index',
            compact(
                'roles',
                'permissions',
                'cms_content',
                'existing_permissions',
                'columns',
                'search'
            )
        );
    }

    final public function store(Request $request): RedirectResponse
    {

        // $this->validate($request, [
        //     'role_permissions' => 'required',
        // ]);
        $request->validate([
            'role_permissions' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $permissions = DB::table('role_has_permissions')->get();

            foreach ($request->input('role_permissions') as $key => $permissions_id) {

                $role = (new RoleExtended())->get_role_by_id($key);
                if ($role) {
                    $given_permission   = [];
                    $removed_permission = [];
                    foreach ($permissions_id as $permission_id => $permission_value) {
                        if ($permission_value == 1) {
                            if (!$permissions->where('permission_id', $permission_id)->where('role_id', $role->id)->first()) {
                                $given_permission[] = $permission_id;
                            }
                        } else {
                            $removed_permission[] = $permission_id;
                        }
                    }
                    $role->permissions()->attach($given_permission);
                    $role->permissions()->detach($removed_permission);
                }
            }
            Artisan::call('optimize:clear');
            DB::commit();
            success_alert('Role permission associated successfully');
            return redirect()->back();
        } catch (\Throwable $throwable) {
            DB::rollBack();
            app_error_log('ROLE_PERMISSION_ASSOCIATION_FAILED', $throwable, 'error');
            success_alert($throwable->getMessage());
            return redirect()->back();
        }
    }


}

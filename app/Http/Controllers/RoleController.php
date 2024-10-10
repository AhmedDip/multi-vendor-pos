<?php

namespace App\Http\Controllers;

use App\Manager\AccessControl\AccessControlTrait;
use App\Models\RoleExtended;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class RoleController extends Controller implements HasMiddleware
{
    // use CommonResponse, AppActivityLog, AccessControlTrait;
    use CommonResponse, AppActivityLog;

    public static string $route = 'role';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request): View
    {
        $cms_content = [
            'module'       => __('Role'),
            'module_url'   => route('role.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title' => __('Create Role'),
            'button_url'   => route('role.create'),
        ];


        $roles   = (new RoleExtended())->get_roles($request);
        $search  = $request->all();
        $columns = [
            'name'       => trans('Name'),
            // 'sort_order' => trans('Sort Order'),
            'status'     => trans('Status'),
        ];
        return view('admin.modules.role.index',
            compact('cms_content', 'roles', 'search', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create(): View
    {
        $cms_content = [
            'module'       => __('Role'),
            'module_url'   => route('role.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title' => __('Role List'),
            'button_url'   => route('role.index'),
        ];

        $role_type   = RoleExtended::ROLE_TYPE_LIST;

        return view('admin.modules.role.create', compact('cms_content', 'role_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $role     = (new RoleExtended())->create_role($request);
            $original = $role->getOriginal();
            $changed  = $role->getChanges();
            self::activityLog($request, $original, $changed, $role);
            success_alert('role created successfully');
            DB::commit();
            return redirect()->route('role.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('role_CREATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    final public function show(RoleExtended $role): View
    {
        $cms_content = [
            'module'       => __('Role'),
            'module_url'   => route('role.index'),
            'active_title' => __('Show'),
            'button_type'  => 'list',
            'button_title' => __('Role List'),
            'button_url'   => route('role.index'),
        ];
        $role->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.role.show',
            compact('role', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(RoleExtended $role): View
    {
        $cms_content = [
            'module'       => __('Role'),
            'module_url'   => route('role.index'),
            'active_title' => 'Edit',
            'button_type'  => 'list',
            'button_title' => __('Role List'),
            'button_url'   => route('role.index'),
        ];
        $role_type   = RoleExtended::ROLE_TYPE_LIST;
        return view('admin.modules.role.edit', compact(
            'cms_content',
            'role',
            'role_type'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateRoleRequest $request, RoleExtended $role): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $role->getOriginal();
            (new RoleExtended())->update_role($request, $role);
            $changed = $role->getChanges();
            self::activityLog($request, $original, $changed, $role);
            DB::commit();
            success_alert('role updated successfully');
            return redirect()->route('role.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('role_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, RoleExtended $role): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $role->getOriginal();
            (new RoleExtended())->delete_role($role);
            $changed = $role->getChanges();
            self::activityLog($request, $original, $changed, $role);
            DB::commit();
            success_alert('role deleted successfully');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('role_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return redirect()->back();
    }
}

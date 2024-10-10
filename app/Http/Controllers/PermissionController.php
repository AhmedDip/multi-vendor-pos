<?php

namespace App\Http\Controllers;

use App\Models\PermissionExtended;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Throwable;

class PermissionController extends Controller
{

    public static string $route = 'permission';

    /**
     * @param Request $request
     * @return View
     */
    final public function index(Request $request): View
    {
        $cms_content = [
            'module'       => __('Permission Extended'),    //page title, breadcrumb second item
            'module_url'   => route('permission.index'),    //breadcrumb second route
            'active_title' => __('List'),                   // create, list, edit, update
            'button_type'  => 'create',                     //create
            'button_title' => __('Generate Permission'),
            'button_url'   => route('permission.create'),
        ];


        $search      = $request->all();
        $columns     = [
            'name'       => trans('Name'),
            'role'       => trans('Role'),
            'created_at' => trans('Created Time'),
            'updated_at' => trans('Updated Time')
        ];
        $permissions = (new PermissionExtended())->get_permissions($request);
        return view('admin.modules.permission.index', compact(
            'columns',
            'cms_content',
            'permissions',
            'search'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create(): View
    {
        $cms_content = [
            'module_name'     => 'Permission',                //page title, breadcrumb second item
            'module_route'    => route('permission.index'),   //breadcrumb second route
            'sub_module_name' => 'Create',                    // create, list, edit, update
            'button_type'     => 'list',                      //create
            'action_route'    => route('permission.index'),
        ];
        return view('admin.modules.permission.create', compact('cms_content'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    final public function store(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $route_names = collect(Route::getRoutes())->map(function ($route) {
                return $route->getName();
            })->filter()->toArray();

            foreach ($route_names as $route_name) {
                $is_existing_permission = Permission::query()->where('name', $route_name)->exists();
                if (!$is_existing_permission) {
                    Permission::create(['name' => $route_name]);
                }
            }
            $permission_delete_query      = Permission::query()->whereNotIn('name', $route_names);
            $role_permission_delete_query = clone $permission_delete_query;
            $permission_delete_query->delete();
            $deleted_permissions = $role_permission_delete_query->select('id')->get()->toArray();
            DB::table('model_has_permissions')->whereIn('permission_id', $deleted_permissions)->delete();

            $message = 'PermissionExtended generated successfully by ' . Auth::user()?->name;
            app_success_log('PERMISSION_GENERATED', $message);
            DB::commit();
            success_alert($message);
            if ($request->input('redirect')) {
                return redirect($request->input('redirect'));
            }
            return redirect()->route('permission.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PERMISSION_GENERATION_FAILED', $throwable, 'error');
            success_alert($throwable->getMessage());
            return redirect()->back();
        }
    }
}

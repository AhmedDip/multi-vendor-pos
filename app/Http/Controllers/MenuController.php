<?php

namespace App\Http\Controllers;

use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;
use Throwable;

class MenuController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'menu';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request): View
    {
        $cms_content = [
            'module'       => __('Menu'),
            'module_url'   => route('menu.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title' => __('Menu Create'),
            'button_url'   => route('menu.create'),
        ];
        $menus       = (new Menu())->get_menus($request);
        $search      = $request->all();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
        ];
        return view('admin.modules.menu.index',
            compact('cms_content', 'menus', 'search', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create(): View
    {
        $cms_content = [
            'module'       => 'Menu',
            'module_url'   => route('menu.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title' => __('Menu List'),
            'button_url'   => route('menu.index'),
        ];
        $menus       = (new Menu())->get_menu_assoc();
        $routes      = collect(Route::getRoutes())->mapWithKeys(function ($route) {
            $formattedName = ucwords(str_replace(['-', '_', '.'], ' ', $route->getName())) . ' - (' . $route->getName() . ')';
            return !empty($route->getName()) ? [$route->getName() => $formattedName] : [];
        })->toArray();
        return view('admin.modules.menu.create', compact(
            'menus', 'routes', 'cms_content'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreMenuRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $menu     = (new Menu())->store_menu($request);
            $original = $menu->getOriginal();
            $changed  = $menu->getChanges();
            self::activityLog($request, $original, $changed, $menu);
            success_alert('Menu created successfully');
            DB::commit();
            return redirect()->route('menu.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('menu_CREATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Menu $menu): View
    {
        $cms_content = [
            'module'       => __('Menu'),
            'module_url'   => route('menu.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title' => __('Menu List'),
            'button_url'   => route('menu.index'),
        ];
        $menu->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.menu.show',
            compact('menu', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Menu $menu): View
    {
        $cms_content = [
            'module'       => __('Menu'),
            'module_url'   => route('menu.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title' => __('Menu List'),
            'button_url'   => route('menu.index'),
        ];
        $menus       = (new Menu())->get_menu_assoc();
        $routes      = collect(Route::getRoutes())->mapWithKeys(function ($route) {
            $formattedName = ucwords(str_replace(['-', '_', '.'], ' ', $route->getName())) . ' - (' . $route->getName() . ')';
            return !empty($route->getName()) ? [$route->getName() => $formattedName] : [];
        })->toArray();
        return view('admin.modules.menu.edit', compact(
            'cms_content',
            'menu',
            'menus',
            'routes'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateMenuRequest $request, Menu $menu): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $menu->getOriginal();
            (new Menu())->update_menu($request, $menu);
            $changed = $menu->getChanges();
            self::activityLog($request, $original, $changed, $menu);
            DB::commit();
            success_alert('Menu updated successfully');
            return redirect()->route('menu.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('menu_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Menu $menu): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $menu->getOriginal();
            (new Menu())->delete_menu($menu);
            $changed = $menu->getChanges();
            self::activityLog($request, $original, $changed, $menu);
            DB::commit();
            success_alert('menu deleted successfully');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('menu_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return redirect()->back();
    }
}

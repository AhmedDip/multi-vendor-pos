<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class WarehouseController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'warehouse';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('warehouse'),
            'module_url'  => route('warehouse.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Warehouse Create'),
            'button_url' => route('warehouse.create'),
        ];
        $warehouses   = (new Warehouse())->get_warehouse($request);
        $search      = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.warehouse.index',
        compact('cms_content', 'warehouses', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('warehouse'),
            'module_url'  => route('warehouse.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Warehouse List'),
            'button_url' => route('warehouse.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.warehouse.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreWarehouseRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $warehouse = (new Warehouse())->store_warehouse($request);
           $original = $warehouse->getOriginal();
           $changed = $warehouse->getChanges();
           self::activityLog($request, $original, $changed, $warehouse);
           success_alert(__('warehouse created successfully'));
           DB::commit();
           return redirect()->route('warehouse.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('warehouse_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Warehouse $warehouse):View
    {
        $cms_content = [
            'module' => __('warehouse'),
            'module_url'  => route('warehouse.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Warehouse List'),
            'button_url' => route('warehouse.index'),
        ];
        $warehouse->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.warehouse.show',
                   compact('warehouse', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Warehouse $warehouse):View
    {
        $cms_content = [
            'module' => __('warehouse'),
            'module_url'  => route('warehouse.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Warehouse List'),
            'button_url' => route('warehouse.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.warehouse.edit', compact(
                    'cms_content',
                    'warehouse','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateWarehouseRequest $request, Warehouse $warehouse):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $warehouse->getOriginal();
            (new Warehouse())->update_warehouse($request, $warehouse);
            $changed = $warehouse->getChanges();
            self::activityLog($request, $original, $changed, $warehouse);
            DB::commit();
            success_alert(__('warehouse updated successfully'));
            return redirect()->route('warehouse.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('warehouse_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Warehouse $warehouse):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $warehouse->getOriginal();
            (new Warehouse())->delete_warehouse($warehouse);
            $changed = $warehouse->getChanges();
            self::activityLog($request, $original, $changed, $warehouse);
            DB::commit();
            success_alert(__('warehouse deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('warehouse_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

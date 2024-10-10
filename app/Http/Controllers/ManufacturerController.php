<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManufacturerRequest;
use App\Http\Requests\UpdateManufacturerRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Manufacturer;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class ManufacturerController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'manufacturer';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('manufacturer'),
            'module_url'  => route('manufacturer.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Manufacturer Create'),
            'button_url' => route('manufacturer.create'),
        ];
        $manufacturers   = (new Manufacturer())->get_manufacturer($request);
        $shops=(new Shop())->get_shops_assoc();
        $search      = $request->all();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.manufacturer.index',
        compact('cms_content', 'manufacturers', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('manufacturer'),
            'module_url'  => route('manufacturer.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Manufacturer List'),
            'button_url' => route('manufacturer.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.manufacturer.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreManufacturerRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $manufacturer = (new Manufacturer())->store_manufacturer($request);
           $original = $manufacturer->getOriginal();
           $changed = $manufacturer->getChanges();
           self::activityLog($request, $original, $changed, $manufacturer);
           success_alert(__('manufacturer created successfully'));
           DB::commit();
           return redirect()->route('manufacturer.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('manufacturer_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Manufacturer $manufacturer):View
    {
        $cms_content = [
            'module' => __('manufacturer'),
            'module_url'  => route('manufacturer.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Manufacturer List'),
            'button_url' => route('manufacturer.index'),
        ];
        $manufacturer->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.manufacturer.show',
                   compact('manufacturer', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Manufacturer $manufacturer):View
    {
        $cms_content = [
            'module' => __('manufacturer'),
            'module_url'  => route('manufacturer.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Manufacturer List'),
            'button_url' => route('manufacturer.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.manufacturer.edit', compact(
                    'cms_content',
                    'manufacturer','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateManufacturerRequest $request, Manufacturer $manufacturer):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $manufacturer->getOriginal();
            (new Manufacturer())->update_manufacturer($request, $manufacturer);
            $changed = $manufacturer->getChanges();
            self::activityLog($request, $original, $changed, $manufacturer);
            DB::commit();
            success_alert(__('manufacturer updated successfully'));
            return redirect()->route('manufacturer.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('manufacturer_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Manufacturer $manufacturer):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $manufacturer->getOriginal();
            (new Manufacturer())->delete_manufacturer($manufacturer);
            $changed = $manufacturer->getChanges();
            self::activityLog($request, $original, $changed, $manufacturer);
            DB::commit();
            success_alert(__('manufacturer deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('manufacturer_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

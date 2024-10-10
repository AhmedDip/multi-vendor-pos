<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Package;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PackageController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'package';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('package'),
            'module_url'  => route('package.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Package Create'),
            'button_url' => route('package.create'),
        ];
        $packages   = (new Package())->get_package($request);
        $search      = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.package.index',
        compact('cms_content', 'packages', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('package'),
            'module_url'  => route('package.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Package List'),
            'button_url' => route('package.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.package.create', compact('cms_content'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StorePackageRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $package = (new Package())->store_package($request);
           $original = $package->getOriginal();
           $changed = $package->getChanges();
           self::activityLog($request, $original, $changed, $package);
           success_alert(__('package created successfully'));
           DB::commit();
           return redirect()->route('package.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('package_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Package $package):View
    {
        $cms_content = [
            'module' => __('package'),
            'module_url'  => route('package.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Package List'),
            'button_url' => route('package.index'),
        ];
        $package->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.package.show',
                   compact('package', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Package $package):View
    {
        $cms_content = [
            'module' => __('package'),
            'module_url'  => route('package.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Package List'),
            'button_url' => route('package.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.package.edit', compact(
                    'cms_content',
                    'package','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdatePackageRequest $request, Package $package):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $package->getOriginal();
            (new Package())->update_package($request, $package);
            $changed = $package->getChanges();
            self::activityLog($request, $original, $changed, $package);
            DB::commit();
            success_alert(__('package updated successfully'));
            return redirect()->route('package.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Package $package):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $package->getOriginal();
            (new Package())->delete_package($package);
            $changed = $package->getChanges();
            self::activityLog($request, $original, $changed, $package);
            DB::commit();
            success_alert(__('package deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

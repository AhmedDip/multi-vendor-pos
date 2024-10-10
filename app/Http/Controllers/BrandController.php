<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Brand;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class BrandController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'brand';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('brand'),
            'module_url'  => route('brand.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Brand Create'),
            'button_url' => route('brand.create'),
        ];
        $brands   = (new Brand())->get_brand($request);
        $search      = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.brand.index',
        compact('cms_content', 'brands', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('brand'),
            'module_url'  => route('brand.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Brand List'),
            'button_url' => route('brand.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.brand.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreBrandRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $brand = (new Brand())->store_brand($request);
           $original = $brand->getOriginal();
           $changed = $brand->getChanges();
           self::activityLog($request, $original, $changed, $brand);
           success_alert(__('brand created successfully'));
           DB::commit();
           return redirect()->route('brand.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('brand_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Brand $brand):View
    {
        $cms_content = [
            'module' => __('brand'),
            'module_url'  => route('brand.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Brand List'),
            'button_url' => route('brand.index'),
        ];
        $brand->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.brand.show',
                   compact('brand', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Brand $brand):View
    {
        $cms_content = [
            'module' => __('brand'),
            'module_url'  => route('brand.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Brand List'),
            'button_url' => route('brand.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.brand.edit', compact(
                    'cms_content',
                    'brand','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateBrandRequest $request, Brand $brand):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $brand->getOriginal();
            (new Brand())->update_brand($request, $brand);
            $changed = $brand->getChanges();
            self::activityLog($request, $original, $changed, $brand);
            DB::commit();
            success_alert(__('brand updated successfully'));
            return redirect()->route('brand.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('brand_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Brand $brand):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $brand->getOriginal();
            (new Brand())->delete_brand($brand);
            $changed = $brand->getChanges();
            self::activityLog($request, $original, $changed, $brand);
            DB::commit();
            success_alert(__('brand deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('brand_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

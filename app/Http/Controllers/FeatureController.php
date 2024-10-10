<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeatureRequest;
use App\Http\Requests\UpdateFeatureRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Feature;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class FeatureController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'feature';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('feature'),
            'module_url'  => route('feature.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Feature Create'),
            'button_url' => route('feature.create'),
        ];
        $features   = (new Feature())->get_feature($request);
        $search     = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns    = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.feature.index',
        compact('cms_content', 'features', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('feature'),
            'module_url'  => route('feature.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Feature List'),
            'button_url' => route('feature.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.feature.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreFeatureRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $feature = (new Feature())->store_feature($request);
           $original = $feature->getOriginal();
           $changed = $feature->getChanges();
           self::activityLog($request, $original, $changed, $feature);
           success_alert(__('feature created successfully'));
           DB::commit();
           return redirect()->route('feature.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('feature_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Feature $feature):View
    {
        $cms_content = [
            'module' => __('feature'),
            'module_url'  => route('feature.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Feature List'),
            'button_url' => route('feature.index'),
        ];
        $feature->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.feature.show',
                   compact('feature', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Feature $feature):View
    {
        $cms_content = [
            'module' => __('feature'),
            'module_url'  => route('feature.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Feature List'),
            'button_url' => route('feature.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.feature.edit', compact(
                    'cms_content',
                    'feature','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateFeatureRequest $request, Feature $feature):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $feature->getOriginal();
            (new Feature())->update_feature($request, $feature);
            $changed = $feature->getChanges();
            self::activityLog($request, $original, $changed, $feature);
            DB::commit();
            success_alert(__('feature updated successfully'));
            return redirect()->route('feature.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('feature_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Feature $feature):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $feature->getOriginal();
            (new Feature())->delete_feature($feature);
            $changed = $feature->getChanges();
            self::activityLog($request, $original, $changed, $feature);
            DB::commit();
            success_alert(__('feature deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('feature_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

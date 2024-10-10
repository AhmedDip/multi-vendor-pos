<?php

namespace App\Http\Controllers;

use App\Manager\AccessControl\AccessControlTrait;
use App\Models\ActivityLog;
use App\Http\Requests\StoreActivityLogRequest;
use App\Http\Requests\UpdateActivityLogRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\API\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class ActivityLogController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'activityLog';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => 'activityLog',
            'module_url'  => route('activityLog.index'),
            'active_title' => 'List',
            'button_type'  => 'create',
            'button_title'  => 'activityLog Create',
            'button_url' => route('activityLog.create'),
        ];
        $activityLogs   = (new ActivityLog())->get_activityLog($request);
        $search      = $request->all();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.activityLog.index',
        compact('cms_content', 'activityLogs', 'search', 'columns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => 'activityLog',
            'module_url'  => route('activityLog.index'),
            'active_title' => 'Create',
            'button_type'  => 'list',
            'button_title'  => 'activityLog List',
            'button_url' => route('activityLog.index'),
        ];
        return view('admin.modules.activityLog.create', compact('cms_content'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreActivityLogRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $activityLog = (new ActivityLog())->store_activityLog($request);
           $original = $activityLog->getOriginal();
           $changed = $activityLog->getChanges();
           self::activityLog($request, $original, $changed, $activityLog);
           success_alert('activityLog created successfully');
           DB::commit();
           return redirect()->route('activityLog.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('activityLog_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(ActivityLog $activityLog):View
    {
        $cms_content = [
            'module' => 'activityLog',
            'module_url'  => route('activityLog.index'),
            'active_title' => 'Details',
            'button_type'  => 'list',
            'button_title'  => 'activityLog List',
            'button_url' => route('activityLog.index'),
        ];
        $activityLog->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.activityLog.show',
                   compact('activityLog', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(ActivityLog $activityLog):View
    {
        $cms_content = [
            'module' => 'activityLog',
            'module_url'  => route('activityLog.index'),
            'active_title' => 'Edit',
            'button_type'  => 'list',
            'button_title'  => 'activityLog List',
            'button_url' => route('activityLog.index'),
        ];
        return view('admin.modules.activityLog.edit', compact(
                    'cms_content',
                    'activityLog',
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateActivityLogRequest $request, ActivityLog $activityLog):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $activityLog->getOriginal();
            (new ActivityLog())->update_activityLog($request, $activityLog);
            $changed = $activityLog->getChanges();
            self::activityLog($request, $original, $changed, $activityLog);
            DB::commit();
            success_alert('activityLog updated successfully');
            return redirect()->route('activityLog.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('activityLog_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(ActivityLog $activityLog):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $activityLog->getOriginal();
            (new ActivityLog())->delete_activityLog($activityLog);
            $changed = $activityLog->getChanges();
            self::activityLog($request, $original, $changed, $activityLog);
            DB::commit();
            success_alert('activityLog deleted successfully');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('activityLog_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Shop;
use App\Models\Tag;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class TagController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'tag';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('tag'),
            'module_url'  => route('tag.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Tag Create'),
            'button_url' => route('tag.create'),
        ];
        $tags   = (new Tag())->get_tag($request);
        $search      = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.tag.index',
        compact('cms_content', 'tags', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('tag'),
            'module_url'  => route('tag.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Tag List'),
            'button_url' => route('tag.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.tag.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreTagRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $tag = (new Tag())->store_tag($request);
           $original = $tag->getOriginal();
           $changed = $tag->getChanges();
           self::activityLog($request, $original, $changed, $tag);
           success_alert(__('tag created successfully'));
           DB::commit();
           return redirect()->route('tag.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('tag_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Tag $tag):View
    {
        $cms_content = [
            'module' => __('tag'),
            'module_url'  => route('tag.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Tag List'),
            'button_url' => route('tag.index'),
        ];
        $tag->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.tag.show',
                   compact('tag', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Tag $tag):View
    {
        $cms_content = [
            'module' => __('tag'),
            'module_url'  => route('tag.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Tag List'),
            'button_url' => route('tag.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.tag.edit', compact(
                    'cms_content',
                    'tag','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateTagRequest $request, Tag $tag):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $tag->getOriginal();
            (new Tag())->update_tag($request, $tag);
            $changed = $tag->getChanges();
            self::activityLog($request, $original, $changed, $tag);
            DB::commit();
            success_alert(__('tag updated successfully'));
            return redirect()->route('tag.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('tag_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Tag $tag):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $tag->getOriginal();
            (new Tag())->delete_tag($tag);
            $changed = $tag->getChanges();
            self::activityLog($request, $original, $changed, $tag);
            DB::commit();
            success_alert(__('tag deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('tag_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Attribute;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Manager\AccessControl\AccessControlTrait;

class AttributeController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'attribute';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('Attribute'),
            'module_url'  => route('attribute.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Attribute Create'),
            'button_url' => route('attribute.create'),
        ];
        $attributes   = (new Attribute())->get_attribute($request);
        $search      = $request->all();
        $shops              = (new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.attribute.index',
        compact('cms_content', 'attributes', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('attribute'),
            'module_url'  => route('attribute.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Attribute List'),
            'button_url' => route('attribute.index'),
        ];

        $shops = (new Shop())->get_shops_assoc();
        
        return view('admin.modules.attribute.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreAttributeRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $attribute = (new Attribute())->store_attribute($request);
           $original = $attribute->getOriginal();
           $changed = $attribute->getChanges();
           self::activityLog($request, $original, $changed, $attribute);
           success_alert(__('attribute created successfully'));
           DB::commit();
           return redirect()->route('attribute.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('attribute_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Attribute $attribute):View
    {
        $cms_content = [
            'module' => __('attribute'),
            'module_url'  => route('attribute.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Attribute List'),
            'button_url' => route('attribute.index'),
        ];
        $attribute->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.attribute.show',
                   compact('attribute', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Attribute $attribute):View
    {
        $cms_content = [
            'module' => __('attribute'),
            'module_url'  => route('attribute.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Attribute List'),
            'button_url' => route('attribute.index'),
        ];
        return view('admin.modules.attribute.edit', compact(
                    'cms_content',
                    'attribute',
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateAttributeRequest $request, Attribute $attribute):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $attribute->getOriginal();
            (new Attribute())->update_attribute($request, $attribute);
            $changed = $attribute->getChanges();
            self::activityLog($request, $original, $changed, $attribute);
            DB::commit();
            success_alert(__('attribute updated successfully'));
            return redirect()->route('attribute.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Attribute $attribute):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $attribute->getOriginal();
            (new Attribute())->delete_attribute($attribute);
            $changed = $attribute->getChanges();
            self::activityLog($request, $original, $changed, $attribute);
            DB::commit();
            success_alert(__('attribute deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }

 

}

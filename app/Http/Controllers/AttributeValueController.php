<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Attribute;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreAttributeValueRequest;
use App\Manager\AccessControl\AccessControlTrait;

class AttributeValueController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'attribute-value';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('Attribute Value'),
            'module_url'  => route('attribute-value.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Attribute Value Create'),
            'button_url' => route('attribute-value.create'),
        ];
        $attribute_values   = (new AttributeValue())->get_attribute_value($request);
        $shops              = (new Shop())->get_shops_assoc();
        $search      = $request->all();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.attribute-value.index',
        compact('cms_content', 'attribute_values', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('Attribute Value'),
            'module_url'  => route('attribute-value.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Attribute Value List'),
            'button_url' => route('attribute-value.index'),
        ];

        $shops = (new Shop())->get_shops_assoc();
        $attributes = (new Attribute())->get_attribute_groupby_shop();

        return view('admin.modules.attribute-value.create', compact('cms_content','shops','attributes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreAttributeValueRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $attribute_value = (new AttributeValue())->store_attribute_value($request);
           $original = $attribute_value->getOriginal();
           $changed = $attribute_value->getChanges();
           self::activityLog($request, $original, $changed, $attribute_value);
           success_alert(__('attributeValue created successfully'));
           DB::commit();
           return redirect()->route('attribute-value.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('attributeValue_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(AttributeValue $attribute_value):View
    {
        $cms_content = [
            'module' => __('Attribute Value'),
            'module_url'  => route('attribute-value.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Attribute Value List'),
            'button_url' => route('attribute-value.index'),
        ];
        $attribute_value->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.attribute-value.show',
                   compact('attribute_value', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(AttributeValue $attribute_value):View
    {
        $cms_content = [
            'module' => __('Attribute Value'),
            'module_url'  => route('attribute-value.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Attribute Value List'),
            'button_url' => route('attribute-value.index'),
        ];
        
        $shops = (new Shop())->get_shops_assoc();
        $attributes = (new Attribute())->get_attribute_groupby_shop();

        return view('admin.modules.attribute-value.edit', compact(
                    'cms_content',
                    'attribute_value',
                    'shops',
                    'attributes'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(Request $request, AttributeValue $attribute_value):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $attribute_value->getOriginal();
            (new AttributeValue())->update_attribute_value($request, $attribute_value);
            $changed = $attribute_value->getChanges();
            self::activityLog($request, $original, $changed, $attribute_value);
            DB::commit();
            success_alert(__('attributeValue updated successfully'));
            return redirect()->route('attribute-value.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attributeValue_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, AttributeValue $attribute_value):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $attribute_value->getOriginal();
            (new AttributeValue())->delete_attribute_value($attribute_value);
            $changed = $attribute_value->getChanges();
            self::activityLog($request, $original, $changed, $attribute_value);
            DB::commit();
            success_alert(__('attributeValue deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attributeValue_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

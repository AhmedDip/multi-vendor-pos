<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShippingMethodRequest;
use App\Http\Requests\UpdateShippingMethodRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\ShippingMethod;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class ShippingMethodController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'shipping-method';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('shippingMethod'),
            'module_url'  => route('shipping-method.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Shipping Method Create'),
            'button_url' => route('shipping-method.create'),
        ];
        $shipping_methods   = (new ShippingMethod())->get_shipping_method($request);
        $search      = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.shipping-method.index',
        compact('cms_content', 'shipping_methods', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('shippingMethod'),
            'module_url'  => route('shipping-method.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Shipping Method List'),
            'button_url' => route('shipping-method.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.shipping-method.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreShippingMethodRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $shipping_method = (new ShippingMethod())->store_shipping_method($request);
           $original = $shipping_method->getOriginal();
           $changed = $shipping_method->getChanges();
           self::activityLog($request, $original, $changed, $shipping_method);
           success_alert(__('shippingMethod created successfully'));
           DB::commit();
           return redirect()->route('shipping-method.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('shippingMethod_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(ShippingMethod $shipping_method):View
    {
        $cms_content = [
            'module' => __('shippingMethod'),
            'module_url'  => route('shipping-method.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Shipping Method List'),
            'button_url' => route('shipping-method.index'),
        ];
        $shipping_method->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.shipping-method.show',
                   compact('shipping_method', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(ShippingMethod $shipping_method):View
    {
        $cms_content = [
            'module' => __('shippingMethod'),
            'module_url'  => route('shipping-method.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Shipping Method List'),
            'button_url' => route('shipping-method.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.shipping-method.edit', compact(
                    'cms_content',
                    'shipping_method','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateShippingMethodRequest $request, ShippingMethod $shipping_method):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $shipping_method->getOriginal();
            (new ShippingMethod())->update_shipping_method($request, $shipping_method);
            $changed = $shipping_method->getChanges();
            self::activityLog($request, $original, $changed, $shipping_method);
            DB::commit();
            success_alert(__('shippingMethod updated successfully'));
            return redirect()->route('shipping-method.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shippingMethod_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, ShippingMethod $shipping_method):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $shipping_method->getOriginal();
            (new ShippingMethod())->delete_shipping_method($shipping_method);
            $changed = $shipping_method->getChanges();
            self::activityLog($request, $original, $changed, $shipping_method);
            DB::commit();
            success_alert(__('shippingMethod deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shippingMethod_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

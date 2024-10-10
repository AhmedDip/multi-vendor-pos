<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\PaymentMethod;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PaymentMethodController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'payment-method';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('paymentMethod'),
            'module_url'  => route('payment-method.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Payment Method Create'),
            'button_url' => route('payment-method.create'),
        ];
        $payment_methods   = (new PaymentMethod())->get_payment_method($request);
        $search      = $request->all();
        $shops=(new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.payment-method.index',
        compact('cms_content', 'payment_methods', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('paymentMethod'),
            'module_url'  => route('payment-method.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Payment Method List'),
            'button_url' => route('payment-method.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.payment-method.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StorePaymentMethodRequest $request):RedirectResponse
    {
        
        try {
           DB::beginTransaction();
           $payment_method = (new PaymentMethod())->store_payment_method($request);
           $original = $payment_method->getOriginal();
           $changed = $payment_method->getChanges();
           self::activityLog($request, $original, $changed, $payment_method);
           success_alert(__('paymentMethod created successfully'));
           DB::commit();
           return redirect()->route('payment-method.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('paymentMethod_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(PaymentMethod $payment_method):View
    {
        $cms_content = [
            'module' => __('paymentMethod'),
            'module_url'  => route('payment-method.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Payment Method List'),
            'button_url' => route('payment-method.index'),
        ];
        $payment_method->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.payment-method.show',
                   compact('payment_method', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(PaymentMethod $payment_method):View
    {
        $cms_content = [
            'module' => __('paymentMethod'),
            'module_url'  => route('payment-method.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Payment Method List'),
            'button_url' => route('payment-method.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.payment-method.edit', compact(
                    'cms_content',
                    'payment_method','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdatePaymentMethodRequest $request, PaymentMethod $payment_method):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $payment_method->getOriginal();
            (new PaymentMethod())->update_payment_method($request, $payment_method);
            $changed = $payment_method->getChanges();
            self::activityLog($request, $original, $changed, $payment_method);
            DB::commit();
            success_alert(__('paymentMethod updated successfully'));
            return redirect()->route('payment-method.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('paymentMethod_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, PaymentMethod $payment_method):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $payment_method->getOriginal();
            (new PaymentMethod())->delete_payment_method($payment_method);
            $changed = $payment_method->getChanges();
            self::activityLog($request, $original, $changed, $payment_method);
            DB::commit();
            success_alert(__('paymentMethod deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('paymentMethod_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

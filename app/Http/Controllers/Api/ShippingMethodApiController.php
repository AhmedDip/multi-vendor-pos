<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShippingMethodRequest;
use App\Http\Requests\UpdateShippingMethodRequest;
use App\Http\Resources\ShippingMethodResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\ShippingMethod;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ShippingMethodApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'shipping-method';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $shipping_method=(new ShippingMethod())->get_shipping_method($request);
            $this->data=ShippingMethodResource::collection($shipping_method);
            $this->status_message = 'ShippingMethod data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shipping_method_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShippingMethodRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $shipping_method=(new ShippingMethod())->store_shipping_method($request);
            $original=$shipping_method->getOriginal();
            $changed=$shipping_method->getChanges();
            self::activityLog($request,$original,$changed,$shipping_method);
            $this->status_message = 'ShippingMethod data Create successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shipping_method_CREATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(ShippingMethod $shipping_method)
    {
        try{
            DB::beginTransaction();
            $this->data=new ShippingMethodResource($shipping_method);
            $this->status_message = 'ShippingMethod data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shipping_method_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShippingMethodRequest $request, ShippingMethod $shipping_method)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original=$shipping_method->getOriginal();
            (new ShippingMethod())->update_shipping_method($request,$shipping_method);
            $changed=$shipping_method->getChanges();
            self::activityLog($request,$original,$changed,$shipping_method);
            $this->status_message = 'ShippingMethod data update successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shipping_method_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShippingMethod $shipping_method,Request $request)
    {
        try {
            DB::beginTransaction();
            (new ShippingMethod())->delete_shipping_method($shipping_method);
            $original=$shipping_method->getOriginal();
            $changed=$shipping_method->getChanges();
            self::activityLog($request,$original,$changed,$shipping_method);
            $this->status_message = 'ShippingMethod deleted successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('shipping_method_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

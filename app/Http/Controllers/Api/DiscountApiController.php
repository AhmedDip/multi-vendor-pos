<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Discount;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DiscountApiController extends Controller
{

    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'discount';

    //todo::need to add activity log in every action

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $discount=(new Discount())->get_discount($request);
            $this->data=DiscountResource::collection($discount);
            $this->status_message = 'Discount data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_fetch_FAILED', $throwable, 'error');
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
    public function store(StoreDiscountRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $discount=(new Discount())->store_discount($request);
            $original = $discount->getOriginal();
            $changed  = $discount->getChanges();
            self::activityLog($request, $original, $changed, $discount);
            $this->status_message = 'Discount data Create successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_CREATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(Discount $discount)
    {
        try{
            DB::beginTransaction();
            $this->data=new DiscountResource($discount);
            $this->status_message = 'Discount data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_fetch_FAILED', $throwable, 'error');
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
    public function update(UpdateDiscountRequest $request, Discount $discount)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original = $discount->getOriginal();
            (new Discount())->update_discount($request,$discount);
            $changed  = $discount->getChanges();
            self::activityLog($request, $original, $changed, $discount);
            $this->status_message = 'Discount data update successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Discount $discount)
    {
        try {
            DB::beginTransaction();
            (new Discount())->delete_discount($discount);
            $original = $discount->getOriginal();
            $changed  = $discount->getChanges();
            self::activityLog($request, $original, $changed, $discount);
            $this->status_message = 'Discount deleted successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

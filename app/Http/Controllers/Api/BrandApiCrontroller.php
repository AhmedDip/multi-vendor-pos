<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrandApiRequest;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Brand;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BrandApiCrontroller extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'brand';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $brand=(new Brand())->get_brand($request);
            $this->data=BrandResource::collection($brand);
            $this->status_message='Brand data fetched successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('brand_fetch_FAILED', $throwable, 'error');
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
    public function store(BrandApiRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new Brand())->store_brand($request);
            $this->status_message = 'Brand data Create successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('brand_CREATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        try{
            DB::beginTransaction();
            $this->data=new BrandResource($brand);
            $this->status_message = 'Brand data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('brand_CREATE_FAILED', $throwable, 'error');
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
    public function update(BrandApiRequest $request, Brand $brand)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $brand = (new Brand())->update_brand($request,$brand);
            $this->status_message = 'Brand data update successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('brand_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,Brand $brand)
    {
        try {
            DB::beginTransaction();
            (new Brand())->delete_brand($brand);
            $original = $brand->getOriginal();
            $changed  = $brand->getChanges();
            self::activityLog($request, $original, $changed, $brand);
            $this->status_message = 'brand deleted successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('brand_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

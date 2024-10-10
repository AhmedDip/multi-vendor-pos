<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeApiRequest;
use App\Http\Requests\StoreAttributeRequest;
use App\Http\Requests\UpdateAttributeRequest;
use App\Http\Resources\AttributeResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Attribute;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AttributeApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route="attribute";
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $attribute=(new Attribute())->get_attribute($request);
            $this->data=AttributeResource::collection($attribute);
            $this->status_message='attribute data fetched successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('attribute_fetch_FAILED', $throwable, 'error');
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
    public function store(AttributeApiRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new Attribute())->store_attribute($request);
            $this->status_message = 'attribute data Create successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_CREATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        try{
            DB::beginTransaction();
            $this->data=new AttributeResource($attribute);
            $this->status_message = 'attribute data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_CREATE_FAILED', $throwable, 'error');
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
    public function update(AttributeApiRequest $request, Attribute $attribute)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new Attribute())->update_attribute($request,$attribute);
            $this->status_message = 'attribute data update successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('attribute_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute,Request $request)
    {
        try {
            DB::beginTransaction();
            (new Attribute())->delete_attribute($attribute);
            $original = $attribute->getOriginal();
            $changed  = $attribute->getChanges();
            self::activityLog($request, $original, $changed, $attribute);
            $this->status_message = 'attribute deleted successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

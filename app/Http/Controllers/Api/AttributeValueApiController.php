<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeApiRequest;
use App\Http\Requests\StoreAttributeValueRequest;
use App\Http\Requests\UpdateAttributeValueRequest;
use App\Http\Resources\AttributeValueResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\AttributeValue;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AttributeValueApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route="attribute-value";

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $attribute=(new AttributeValue())->get_attribute_value($request);
            $this->data=AttributeValueResource::collection($attribute);
            $this->status_message='attribute value data fetched successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('attribute_value_fetch_FAILED', $throwable, 'error');
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
            (new AttributeValue())->store_attribute_value($request);
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
    public function show(AttributeValue $attribute_value)
    {
        try{
            DB::beginTransaction();
            $this->data=new AttributeValueResource($attribute_value);
            $this->status_message = 'attribute value data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_value_FETCH_FAILED', $throwable, 'error');
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
    public function update(UpdateAttributeValueRequest $request, AttributeValue $attribute_value)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new AttributeValue())->update_attribute_value($request,$attribute_value);
            $this->status_message = 'attribute value data update successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('attribute_value_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttributeValue $attribute_value, Request $request)
    {
        try {
            DB::beginTransaction();
            (new AttributeValue())->delete_attribute_value($attribute_value);
            $original = $attribute_value->getOriginal();
            $changed  = $attribute_value->getChanges();
            self::activityLog($request, $original, $changed, $attribute_value);
            $this->status_message = 'attribute value deleted successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('attribute_value_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

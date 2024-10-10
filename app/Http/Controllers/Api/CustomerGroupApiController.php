<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Http\Resources\CustomerGroupResource;

class CustomerGroupApiController extends Controller
{
    use CommonResponse, AppActivityLog;

        /**
     * Display a listing of the resource.
     */
    final public function index(Request $request)
    { 
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $customerGroups   = (new CustomerGroup())->get_customerGroup($request);
           
            $columns     = [
                'name'       => 'Name',
                'status'     => 'Status',
                'shop_id'    => 'Shop Id',
                ];
            $this->data = [
                'customer_group' => CustomerGroupResource::collection($customerGroups)->response()->getData(),
                'columns'        => $columns
                ];
                $this->status_message = 'Customer group data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_Group_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
       
    }

   

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreCustomerGroupRequest $request)
    {
        try {
           DB::beginTransaction();
        //    $request->merge(['shop_id' => $request->header('shop-id')]);
           $customerGroup = (new CustomerGroup())->store_customerGroup($request);
           $original      = $customerGroup->getOriginal();
           $changed       = $customerGroup->getChanges();
           self::activityLog($request,$original,$changed,$customerGroup);
           $this->status_message = 'Customer group data Create successfully';
           DB::commit();
       } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Customer_Group_data_Create_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
       }
       return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    final public function show(CustomerGroup $customerGroup){
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            // $customerGroup   = new CustomerGroup(); 
            $columns     = [
                'name'       => 'Name',
                'status'     => 'Status',
                'shop_id'    => 'Shop Id',
                ];
            $this->data = [
                'customer_group' => new CustomerGroupResource($customerGroup),
                'columns'        => $columns
                ];
                $this->status_message = 'Customer group data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_Group_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(CustomerGroup $customerGroup)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup)
    {
        try {
            DB::beginTransaction();
            $original = $customerGroup->getOriginal();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new CustomerGroup())->update_customerGroup($request, $customerGroup);
            $changed = $customerGroup->getChanges();
            self::activityLog($request, $original, $changed, $customerGroup);
            DB::commit();
            $this->status_message = 'Customer group data Update successfully';
        } catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_Group_data_Update_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy( CustomerGroup $customerGroup)
    {
        try {
            DB::beginTransaction();
            (new CustomerGroup())->delete_customerGroup($customerGroup);
            DB::commit();
            $this->status_message = 'Customer group data Delete successfully';
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('customerGroup_DELETE_FAILED_API', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return $this->commonApiResponse();
           
    }
    
}

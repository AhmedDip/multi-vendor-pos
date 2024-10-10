<?php

namespace App\Http\Controllers\Api;

use Throwable;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApiRequest;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;

class CustomerApiController extends Controller
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
            $customers   = (new Customer())->get_customer($request);
           
    
            $this->data =  CustomerResource::collection($customers)->response()->getData();

            $this->status_message = 'Customer  data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
       
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create()
    {
   
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(CustomerApiRequest $request)
    {
        try {
           DB::beginTransaction();
        //    $request->merge(['shop_id' => $request->header('shop-id')]);
           $customer        = (new Customer())->store_customer($request);

           $original = $customer->getOriginal();
           $changed  = $customer->getChanges();
           self::activityLog($request, $original, $changed, $customer);
           $this->status_message = 'Customer data Create successfully';
           DB::commit();
       } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Customer_data_Create_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
       }
       return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    final public function show(Customer $customer){
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            
            $columns     = [
                'name'               => 'Name',
                'phone'              => 'Phone',
                'address'            => 'Address',
                'shop_id'            => 'Shop Id',
                'membership_card_id' => 'Membership Card No',
                'status'             => 'status',
                ];
            $this->data = [
                'customer' => new CustomerResource($customer),
                'columns'        => $columns
                ];
                $this->status_message = 'Customer data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }

    
    /**
     * Update the specified resource in storage.
     */
    final public function update(CustomerApiRequest $request, Customer $customer)
    {
        try {
            DB::beginTransaction();
            $original = $customer->getOriginal();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new Customer())->update_customer($request, $customer);
            $changed = $customer->getChanges();
            self::activityLog($request, $original, $changed, $customer);
            DB::commit();
            $this->status_message = 'Customer data Update successfully';
        } catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_data_Update_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy( Customer $customer)
    {
        try {
            DB::beginTransaction();
            (new Customer())->delete_customer($customer);
            DB::commit();
            $this->status_message = 'Customer data Delete successfully';
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('customer_DELETE_FAILED_API', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return $this->commonApiResponse();
           
    }

    public function get_customer_info_by_phone(Request $request, $phone){
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $customer = (new Customer())->get_customer_info_by_phone($request, $phone);
            $this->status_message = 'Customer data fetched successfully';
            $this->data           = [
                'customer' => new CustomerResource($customer),
            ];
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Customer_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    
    }


    public function get_customer_dependency_data(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = (new Customer())->get_customer_dependency_data($request);
            $this->status_message = 'Customer dependency data fetched successfully.';
            $this->data = $data;
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('CUSTOMER_DEPENDENCY_DATA_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }


    
}

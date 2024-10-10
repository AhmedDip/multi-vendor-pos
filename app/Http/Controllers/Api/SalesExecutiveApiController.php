<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Middleware\ShopIDMiddleware;
use App\Http\Requests\SalesExecutiveRequest;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\UserDetailsResource;
use App\Manager\AccessControl\AccessControlTrait;


class SalesExecutiveApiController extends Controller
{

    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'sales-executive-api';
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $sales_executives = (new User())->get_sales_executives($request);
            $this->status_message = 'Sales executives fetched successfully.';
            $this->data           = UserDetailsResource::collection($sales_executives);
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
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
    public function store(SalesExecutiveRequest $request)
    {
        try{
            DB::beginTransaction();
            $original = $request->all();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $sales_executive = (new User())->store_sales_executive($request);
            $changed = $sales_executive->getChanges();
            self::activityLog($request, $original, $changed, $sales_executive);
            $this->data = new UserDetailsResource($sales_executive);
          
            $this->status_message = 'Congratulations! Your sales executive has been successfully Added.';
           
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_STORE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $sales_executive)
    {
        try {
            DB::beginTransaction();
            $this->data = new UserDetailsResource($sales_executive);
            $this->status_message = 'Sales executive fetched successfully.';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
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
    public function update(Request $request, int $id)
    {
       
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $original = $user->toArray();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $user = (new User())->update_sales_executive($request, $user);
            $changed = $user->getChanges();
            self::activityLog($request, $original, $changed, $user);
            $this->status_message = 'Congratulations! Your sales executive has been successfully updated.';
            $this->data = new UserDetailsResource($user);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_UPDATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $sales_executive)
    {
        try {
            DB::beginTransaction();

            (new User())->delete_user($sales_executive);

            $this->status_message = 'The sales executive has been successfully deleted.';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_DELETE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
    }

    public function get_sales_executive_dependency_data(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = (new User())->get_sales_executive_dependency_data();
            $this->status_message = 'Sales executive dependency data fetched successfully.';
            $this->data = $data;
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_DEPENDENCY_DATA_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
    }

    public function get_employee_data(Request $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $data = (new User())->get_employee_data($request);
            $this->status_message = 'Employee data fetched successfully.';
            $this->data = $data;
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('EMPLOYEE_DATA_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
    }


}

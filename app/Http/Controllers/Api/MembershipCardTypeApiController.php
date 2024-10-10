<?php

namespace App\Http\Controllers\api;
use Throwable;

use Illuminate\Http\Request;
use App\Models\MembershipCardType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\MembershipCardTypeResource;
use App\Http\Requests\StoreMembershipCardTypeRequest;
use App\Http\Requests\UpdateMembershipCardTypeRequest;

class MembershipCardTypeApiController extends Controller
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
            $membershipCardTypes   = (new MembershipCardType())->get_membershipCardType($request);
           
            $columns     = [
                'card_type_name' => 'card_type_name',
                'discount'       => 'discount',
                'shop_id'        => 'shop_id',
                'status'         => 'Status',
                ];
            $this->data = [
                'membership_card_type' => MembershipCardTypeResource::collection($membershipCardTypes)->response()->getData(),
                'columns'        => $columns
                ];
                $this->status_message = 'Membership Card Type data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Membership_card_type_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
       
    }

    final public function store(StoreMembershipCardTypeRequest $request)
    {
        // dd($request->all());
        try {
           DB::beginTransaction();
        //    $request->merge(['shop_id' => $request->header('shop-id')]);
           $membershipCardType = (new MembershipCardType())->store_membershipCardType($request);
           $original = $membershipCardType->getOriginal();
           $changed = $membershipCardType->getOriginal();
           self::activityLog($request,$original,$changed,$membershipCardType);
           $this->status_message = ('Membership Card Type created successfully');
           DB::commit();
       } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('CMembership_Card_Type_data_Create_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
       return $this->commonApiResponse();
    }

    final public function show(MembershipCardType $membershipCardType){
        try{
            DB::beginTransaction();
            
            $columns     = [
                'card_type_name' => 'card_type_name',
                'discount'       => 'discount',
                'shop_id'        => 'shop_id',
                'status'         => 'Status',
                ];
            $this->data = [
                'membership_card_type' => new MembershipCardTypeResource($membershipCardType),
                'columns'        => $columns
                ];
                $this->status_message = 'Membership Card Type data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Membership_card_type_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }


    final public function update(StoreMembershipCardTypeRequest $request, MembershipCardType $membershipCardType)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new MembershipCardType())->update_membershipCardType($request, $membershipCardType);
       
            DB::commit();
            $this->status_message = 'Membership Card Type data Update successfully';;
        } catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Membership_card_type_data_Update_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }


    final public function destroy( MembershipCardType $membershipCardType)
    {
        try {
            DB::beginTransaction();
            (new MembershipCardType())->delete_membershipCardType($membershipCardType);
            DB::commit();
            $this->status_message = 'Membership Card Type data Delete successfully';
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Membership_card_type_data_DELETE_FAILED_API', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return $this->commonApiResponse();
           
    }

    
}

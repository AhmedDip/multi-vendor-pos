<?php

namespace App\Http\Controllers\api;
use Throwable;

use Illuminate\Http\Request;;
use App\Models\MembershipCard;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\MembershipCardResource;
use App\Http\Requests\StoreMembershipCardRequest;
use App\Http\Requests\UpdateMembershipCardRequest;

class MembershipCardApiController extends Controller
{
    use CommonResponse,AppActivityLog;

    final public function index(Request $request)
    { 
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $membershipCards       = (new MembershipCard())->get_membershipCard($request);
           
            $columns     = [
                'card_no'                 => 'Card No',
                'membership_card_type_id' => 'Membership Card Type Id',
                'status'                  => 'Status',
                'shop_id'                 => 'Shop Id',
                ];
            $this->data = [
                'membership_card' => MembershipCardResource::collection($membershipCards)->response()->getData(),
                'columns'        => $columns
                ];
                $this->status_message = 'Membership Card  data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Membership_card_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
       
    }

    final public function store(StoremembershipCardRequest $request)
    {
        // dd($request->all());
        try {
           DB::beginTransaction();
           
        //    $request->merge(['shop_id' => $request->header('shop-id')]);
           $membershipCard = (new membershipCard())->store_membershipCard($request);
           $original = $membershipCard->getOriginal();
           $changed = $membershipCard->getChanges();
           self::activityLog($request,$original,$changed,$membershipCard);

           $this->status_message = ('Membership Card created successfully');
           DB::commit();
       } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Membership_Card_data_Create_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
       return $this->commonApiResponse();
    }

    final public function show(membershipCard $membershipCard){
        try{
            DB::beginTransaction();
            
            $columns     = [
                'card_no'                 => 'Card No',
                'membership_card_type_id' => 'Membership Card Type Id',
                'status'                  => 'Status',
                'shop_id'                 => 'Shop Id',
                ];
            $this->data = [
                'membership_card' => new MembershipCardResource($membershipCard),
                // 'membership_card' => MembershipCardResource::collection($membershipCards)->response()->getData(),
                'columns'         => $columns
                ];
                $this->status_message = 'Membership Card data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Membership_card_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }


    final public function update(UpdatemembershipCardRequest $request, membershipCard $membershipCard)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original = $membershipCard->getOriginal();
            (new membershipCard())->update_membershipCard($request, $membershipCard);
            $changed = $membershipCard->getChanges();
            self::activityLog($request,$original,$changed,$membershipCard);
       
            DB::commit();
            $this->status_message = 'Membership Card data Update successfully';;
        } catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Membership_card_data_Update_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }



    final public function destroy( membershipCard $membershipCard)
    {
        try {
            DB::beginTransaction();
            (new membershipCard())->delete_membershipCard($membershipCard);
            DB::commit();
            $this->status_message = 'Membership Card data DELETE successfully';
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Membership_card_data_DELETE_FAILED_API', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return $this->commonApiResponse();
           
    }
}

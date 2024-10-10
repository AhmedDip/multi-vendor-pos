<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\MembershipCard;
// use App\Manager\API\Traits\AppActivityLog;
use App\Models\MembershipCardType;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreMembershipCardRequest;

use App\Manager\AccessControl\AccessControlTrait;
use App\Http\Requests\UpdateMembershipCardRequest;

class MembershipCardController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'membership-card';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('Membership Card'),
            'module_url'    => route('membership-card.index'),
            'active_title'  => __('List'),
            'button_type'   => 'create',
            'button_title'  => __('Membership Card Create'),
            'button_url'    => route('membership-card.create'),
        ];
        $membershipCards        = (new MembershipCard())->get_membershipCard($request);
        // $membership_card_type   = (new MembershipCardType)->get_membershiptype();
        $search                 = $request->all();
        $columns     = [
            'card_no'                 => 'Card No',
            'membership_card_type_id' => 'Membership Card Type',
            'status'                  => 'Status',
            'shop_id'                 => 'Shop',
            ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.membershipCard.index',
        compact('cms_content', 'membershipCards', 'search',
         'columns','shop'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module'        => __('Membership Card'),
            'module_url'    => route('membership-card.index'),
            'active_title'  => __('Create'),
            'button_type'   => 'list',
            'button_title'  => __('Membership Card List'),
            'button_url'    => route('membership-card.index'),
        ];
        
        $membership_card_type = (new MembershipCardType)->get_membershiptype_for_card();
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.membershipCard.create', compact('cms_content',
         'membership_card_type','shop'));
    
    }


    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreMembershipCardRequest $request):RedirectResponse
    {
        dd($request->all());
        try {
           DB::beginTransaction();
           $membershipCard = (new MembershipCard())->store_membershipCard($request);
           $original = $membershipCard->getOriginal();
           $changed = $membershipCard->getChanges();
           self::activityLog($request, $original, $changed, $membershipCard);
           success_alert(__('Membership Card created successfully'));
           DB::commit();
           return redirect()->route('membership-card.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('membershipCard_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(MembershipCard $membershipCard):View
    {
        $cms_content = [
            'module'        => __('Membership Card'),
            'module_url'    => route('membership-card.index'),
            'active_title'  => __('Details'),
            'button_type'   => 'list',
            'button_title'  => __('Membership Card List'),
            'button_url'    => route('membership-card.index'),
        ];
        $membershipCard->load(['activity_logs', 'created_by', 'updated_by','membershipCardType']);

        return view('admin.modules.membershipCard.show',
                   compact('membershipCard', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(MembershipCard $membershipCard):View
    {
        $cms_content = [
            'module'        => __('Membership Card'),
            'module_url'    => route('membership-card.index'),
            'active_title'  => __('Edit'),
            'button_type'   => 'list',
            'button_title'  => __('Membership Card List'),
            'button_url'    => route('membership-card.index'),
        ];
        // $membership_card_type = (new MembershipCardType)->get_membershiptype_for_card();
        $membership_card_type = (new MembershipCardType)->get_membershiptype_for_card();
        $shop                 = (new Shop)->getAllShopsAssoc();
        $selectedMembershipCardTypeId = $membershipCard->membership_card_type_id;
        return view('admin.modules.membershipCard.edit', compact('cms_content', 'membershipCard', 'membership_card_type','shop','selectedMembershipCardTypeId'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateMembershipCardRequest $request, MembershipCard $membershipCard):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $membershipCard->getOriginal();
            (new MembershipCard())->update_membershipCard($request, $membershipCard);
            $changed = $membershipCard->getChanges();
            self::activityLog($request, $original, $changed, $membershipCard);
            DB::commit();
            success_alert(__('Membership Card updated successfully'));
            return redirect()->route('membership-card.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('membershipCard_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, MembershipCard $membershipCard):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $membershipCard->getOriginal();
            (new MembershipCard())->delete_membershipCard($membershipCard);
            $changed = $membershipCard->getChanges();
            self::activityLog($request, $original, $changed, $membershipCard);
            DB::commit();
            success_alert(__('Membership Card deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('membershipCard_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }

    // public function getShopsByMembershipCardType($membershipCardTypeId)
    // {
    //     $shops = Shop::where('membership_card_type_id', $membershipCardTypeId)->pluck('name', 'id');
    //     return response()->json($shops);
    // }

    // public function getShopsByMembershipCardType($membershipCardTypeId)
    // {
    //     $membershipCardType = MembershipCardType::find($membershipCardTypeId);
    //     if (!$membershipCardType) {
    //         return response()->json([]);
    //     }
    
    //     $shops = $membershipCardType->shops()->pluck('name', 'id');
    
    //     return response()->json($shops);
    // }
 

}



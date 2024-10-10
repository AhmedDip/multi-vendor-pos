<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use Illuminate\View\View;
use Illuminate\Http\Request;
// use App\Manager\API\Traits\AppActivityLog;
// use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response;
use App\Models\MembershipCardType;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Exports\MembershipCardTypesExport;
use App\Manager\API\Traits\CommonResponse;

use App\Manager\AccessControl\AccessControlTrait;
use App\Http\Requests\StoreMembershipCardTypeRequest;
use App\Http\Requests\UpdateMembershipCardTypeRequest;


class MembershipCardTypeController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'membership-card-type';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('Membership Card Type'),
            'module_url'    => route('membership-card-type.index'),
            'active_title'  => __('List'),
            'button_type'   => 'create',
            'button_title'  => __('Membership Card Type Create'),
            'button_url'    => route('membership-card-type.create'),
        ];
        $membershipCardTypes   = (new MembershipCardType())->get_membershipCardType($request);
        $search      = $request->all();
        $columns     = [
            'card_type_name' => 'Card Type Name',
            'discount'       => 'Discount',
            'shop_id'        => 'Shop',
            'status'         => 'Status',
            
            ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.membershipCardType.index',
        compact('cms_content', 'membershipCardTypes', 'search',
         'columns', 'shop'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module'       => __('Membership Card Type'),
            'module_url'   => route('membership-card-type.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title' => __('Membership Card Type List'),
            'button_url'   => route('membership-card-type.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.membershipCardType.create', compact('cms_content','shop'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreMembershipCardTypeRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $membershipCardType = (new MembershipCardType())->store_membershipCardType($request);
           $original = $membershipCardType->getOriginal();
           $changed = $membershipCardType->getChanges();
           self::activityLog($request, $original, $changed, $membershipCardType);
           success_alert(__('membershipCardType created successfully'));
           DB::commit();
           return redirect()->route('membership-card-type.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('membershipCardType_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(MembershipCardType $membershipCardType):View
    {
        $cms_content = [
            'module'        => __('Membership Card Type'),
            'module_url'    => route('membership-card-type.index'),
            'active_title'  => __('Details'),
            'button_type'   => 'list',
            'button_title'  => __('Membership Card Type List'),
            'button_url'    => route('membership-card-type.index'),
        ];
        $membershipCardType->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.membershipCardType.show',
                   compact('membershipCardType', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(MembershipCardType $membershipCardType):View
    {
        $cms_content = [
            'module'        => __('Membership Card Type'),
            'module_url'    => route('membership-card-type.index'),
            'active_title'  => __('Edit'),
            'button_type'   => 'list',
            'button_title'  => __('Membership Card Type List'),
            'button_url'    => route('membership-card-type.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.membershipCardType.edit', compact(
                    'cms_content',
                    'membershipCardType', 'shop'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateMembershipCardTypeRequest $request, MembershipCardType $membershipCardType):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $membershipCardType->getOriginal();
            (new MembershipCardType())->update_membershipCardType($request, $membershipCardType);
            $changed = $membershipCardType->getChanges();
            self::activityLog($request, $original, $changed, $membershipCardType);
            DB::commit();
            success_alert(__('membershipCardType updated successfully'));
            return redirect()->route('membership-card-type.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('membershipCardType_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, MembershipCardType $membershipCardType):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $membershipCardType->getOriginal();
            (new MembershipCardType())->delete_membershipCardType($membershipCardType);
            $changed = $membershipCardType->getChanges();
            self::activityLog($request, $original, $changed, $membershipCardType);
            DB::commit();
            success_alert(__('membershipCardType deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('membershipCardType_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }



  
}

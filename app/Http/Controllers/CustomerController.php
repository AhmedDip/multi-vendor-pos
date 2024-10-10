<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\MembershipCard;

// use App\Manager\API\Traits\AppActivityLog;
use App\Models\MembershipCardType;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Manager\AccessControl\AccessControlTrait;

class CustomerController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'customer';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('customer'),
            'module_url'    => route('customer.index'),
            'active_title'  => __('List'),
            'button_type'   => 'create',
            'button_title'  => __('customer Create'),
            'button_url'    => route('customer.create'),
        ];
        $customers   = (new Customer())->get_customer($request);
        $search      = $request->all();
        $columns     = [
            'name'               => 'Name',
            'phone'              => 'Phone',
            'address'            => 'Address',
            'shop_id'            => 'Shop',
            'membership_card_id' => 'Membership Card No',
            'status'             => 'status',
            // 'sort_order'         => 'Sort Order',
            ];
        $membership_card_no = (new MembershipCard)->get_membershipcardno();
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.customer.index',
        compact('cms_content', 'customers', 'search',
         'columns', 'membership_card_no','shop'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module'        => __('customer'),
            'module_url'    => route('customer.index'),
            'active_title'  => __('Create'),
            'button_type'   => 'list',
            'button_title'  => __('customer List'),
            'button_url'    => route('customer.index'),
        ];
        $membership_card_no = (new MembershipCard)->get_membershipcard_No_for_customer();
    
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.customer.create', compact('cms_content',
        'membership_card_no','shop'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreCustomerRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $customer = (new Customer())->store_customer($request);
           $original = $customer->getOriginal();
           $changed = $customer->getChanges();
           self::activityLog($request, $original, $changed, $customer);
           success_alert(__('customer created successfully'));
           DB::commit();
           return redirect()->route('customer.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('customer_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Customer $customer):View
    {
        $cms_content = [
            'module'        => __('customer'),
            'module_url'    => route('customer.index'),
            'active_title'  => __('Details'),
            'button_type'   => 'list',
            'button_title'  => __('customer List'),
            'button_url'    => route('customer.index'),
        ];
        
        $customer->load(['activity_logs', 'created_by', 'updated_by']);
       

        return view('admin.modules.customer.show',
                   compact('customer', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Customer $customer):View
    {
        $cms_content = [
            'module'        => __('customer'),
            'module_url'    => route('customer.index'),
            'active_title'  => __('Edit'),
            'button_type'   => 'list',
            'button_title'  => __('customer List'),
            'button_url'    => route('customer.index'),
        ];
        $membership_card_no = (new MembershipCard)->get_membershipcard_No_for_customer();
        $selected_card_no = $customer->membership_card_id;
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.customer.edit', compact(
                    'cms_content',
                    'customer',
                    'membership_card_no','shop',
                    'selected_card_no'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateCustomerRequest $request, Customer $customer):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $customer->getOriginal();
            (new Customer())->update_customer($request, $customer);
            $changed = $customer->getChanges();
            self::activityLog($request, $original, $changed, $customer);
            DB::commit();
            success_alert(__('customer updated successfully'));
            return redirect()->route('customer.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('customer_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Customer $customer):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $customer->getOriginal();
            (new Customer())->delete_customer($customer);
            $changed = $customer->getChanges();
            self::activityLog($request, $original, $changed, $customer);
            DB::commit();
            success_alert(__('customer deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('customer_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }


    public function getCustomerName(Request $request)
    {
        $phone = $request->query('phone');

        if (!$phone) {
            return response()->json(['name' => null]);
        }

        $customer = Customer::where('phone', $phone)->first();

        if ($customer) {
            return response()->json(['name' => $customer->name]);
        } else {
            return response()->json(['name' => null]);
        }
    }


       
}

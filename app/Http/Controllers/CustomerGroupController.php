<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\CustomerGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
// use App\Manager\API\Traits\AppActivityLog;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\CustomerGroupRequest;
use App\Http\Resources\CustomerGroupResource;
use App\Http\Requests\StoreCustomerGroupRequest;
use App\Http\Requests\UpdateCustomerGroupRequest;
use App\Manager\AccessControl\AccessControlTrait;



class CustomerGroupController extends Controller
{
    use CommonResponse, AppActivityLog;

    public static string $route = 'customer-group';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('Customer Group'),
            'module_url'  => route('customer-group.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Customer Group Create'),
            'button_url' => route('customer-group.create'),
            
        ];
        $customerGroups   = (new CustomerGroup())->get_customerGroup($request);
        $search      = $request->all();
        $columns     = [
            'name'       => 'Name',
            'status'     => 'Status',
            'shop_id'    => 'Shop',
            ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.customerGroup.index',
        compact('cms_content', 'customerGroups', 'search',
         'columns','shop'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('Customer Group'),
            'module_url'  => route('customer-group.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Customer Group List'),
            'button_url' => route('customer-group.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.customerGroup.create', compact('cms_content','shop'));

      
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreCustomerGroupRequest $request):RedirectResponse
    {
        // dd($request->all());
        try {
           DB::beginTransaction();
           $customerGroup = (new CustomerGroup())->store_customerGroup($request);
           $original = $customerGroup->getOriginal();
           $changed = $customerGroup->getChanges();
           self::activityLog($request, $original, $changed, $customerGroup);
           success_alert(__('Customer Group created successfully'));
           DB::commit();
           return redirect()->route('customer-group.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('customerGroup_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(CustomerGroup $customerGroup):View
    {
        $cms_content = [
            'module' => __('Customer Group'),
            'module_url'  => route('customer-group.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('customer Group List'),
            'button_url' => route('customer-group.index'),
        ];
        $customerGroup->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.customerGroup.show',
                   compact('customerGroup', 'cms_content'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(CustomerGroup $customerGroup):View
    {
        $cms_content = [
            'module' => __('Customer Group'),
            'module_url'  => route('customer-group.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Customer Group List'),
            'button_url' => route('customer-group.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.customerGroup.edit', compact(
                    'cms_content',
                    'customerGroup','shop'
                ));      
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateCustomerGroupRequest $request, CustomerGroup $customerGroup):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $customerGroup->getOriginal();
            (new CustomerGroup())->update_customerGroup($request, $customerGroup);
            $changed = $customerGroup->getChanges();
            self::activityLog($request, $original, $changed, $customerGroup);
            DB::commit();
            success_alert(__('customerGroup updated successfully'));
            return redirect()->route('customerGroup.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('customerGroup_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }

        
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, CustomerGroup $customerGroup):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $customerGroup->getOriginal();
            (new CustomerGroup())->delete_customerGroup($customerGroup);
            $changed = $customerGroup->getChanges();
            self::activityLog($request, $original, $changed, $customerGroup);
            DB::commit();
            success_alert(__('customerGroup deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('customerGroup_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
   
}

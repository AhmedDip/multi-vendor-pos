<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use App\Http\Requests\ShopOwnerBackendRequest;
use App\Manager\AccessControl\AccessControlTrait;

class ShopOwnerController extends Controller
{
    Use AppActivityLog, AccessControlTrait;

    public static string $route = 'shop-owner';


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cms_content = [
            'module'       => __('Shop Owner'),
            'module_url'   => route('shop-owner.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title' => __('Create Shop Owner'),
            'button_url'   => route('shop-owner.create'),
        ];

        $request->merge([
            'order_by_column' => $request->input('order_by_column', 'id'),
            'order_by'        => $request->input('order_by', 'desc'),
        ]);

        $shop_owners = (new User())->get_shop_owners_data($request);
        $search      = $request->all();
        $shops       = (new Shop())->getAllShopsAssoc();

        $columns = [
            'id'          => 'ID',
            'name'        => 'Name',
            'email'       => 'Email',
            'phone'       => 'Phone',
            'status'      => 'Status',
            'created_at'  => 'Created At',
        ];
        return view('admin.modules.shop-owner.index', compact('cms_content', 'shop_owners', 'columns','search','shops'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cms_content = [
            'module'       => __('Shop Owner'),
            'module_url'   => route('shop-owner.index'),
            'active_title' => __('Create Shop Owner'),
            'button_type'  => 'list',
            'button_title' => __('Shop Owner List'),
            'button_url'   => route('shop-owner.index'),
        ];

        $shops = (new Shop())->getAllShopsAssoc();
     
        $selected_shop = [];

        return view('admin.modules.shop-owner.create', compact('cms_content','shops','selected_shop'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShopOwnerBackendRequest $request)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();
            $original = $request->all();
            $shop_owner = (new User())->store_shop_owner($request);
            $changed         = $shop_owner->getChanges();
            self::activityLog($request, $original, $changed, $shop_owner);
            success_alert('Congratulations! Shop Owner has been successfully created.');
            DB::commit();
            return redirect()->route('shop-owner.index');
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('SHOP_OWNER_STORE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $shop_owner)
    {
        $cms_content = [
            'module'       => __('Shop Owner Show'),
            'module_url'   => route('shop-owner.index'),
            'active_title' => __('View'),
            'button_type'  => 'back',
            'button_title' => __('Back'),
            'button_url'   => route('shop-owner.index'),
        ];

        return view('admin.modules.shop-owner.show', compact('cms_content', 'shop_owner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $shop_owner)
    {
        $cms_content = [
            'module'       => __('Shop Owner'),
            'module_url'   => route('shop-owner.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'back',
            'button_title' => __('Back'),
            'button_url'   => route('shop-owner.index'),
        ];

        $shops = (new Shop())->getAllShopsAssoc();

        $selected_shop = $shop_owner->shops->pluck('id')->toArray();

        return view('admin.modules.shop-owner.edit', compact('cms_content', 'shop_owner', 'shops', 'selected_shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $shop_owner)
    {
        try{
            DB::beginTransaction();
            $original   = $shop_owner->toArray();
            $shop_owner = (new User())->update_shop_owner($request, $shop_owner);
            $changed    = $shop_owner->getChanges();
            self::activityLog($request, $original, $changed, $shop_owner);
            success_alert('Congratulations! Your shop has been successfully updated.');
            DB::commit();
            return redirect()->route('shop-owner.index');
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('SHOP_OWNER_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $shop_owner)
    {
        try{
            DB::beginTransaction();
            $shop_owner = (new User())->delete_user($shop_owner);
           
            success_alert('Congratulations! Shop Owner has been successfully deleted.');
            DB::commit();
            return redirect()->route('shop-owner.index');
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('SHOP_OWNER_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }
}

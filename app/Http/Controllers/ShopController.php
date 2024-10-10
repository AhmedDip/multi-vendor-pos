<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use Throwable;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ShopResource;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;

class ShopController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'shop';

    public function index(Request $request)
    {
        $cms_content = [
            'module'       => __('Shop'),
            'module_url'   => route('shop.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title' => __('Create Shop'),
            'button_url'   => route('shop.create'),
        ];

        // $request->merge([
        //     'order_by_column' => $request->input('order_by_column', 'id'),
        //     'order_by'        => $request->input('order_by', 'desc'),
        // ]);

        $shops = (new Shop())->get_shops($request);
        $columns = [
            // 'id'          => 'ID',
            'name'        => 'Name',
            'phone'       => 'Phone',
            'email'       => 'Email',
            'address'     => 'Address',
            'status'      =>'Status',
            
           
        ];
        $search = $request->all();

        return view('admin.modules.shop.index', compact('shops', 'search', 'cms_content','columns'));



        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cms_content = [
            'module'       => __('Shop'),
            'module_url'   => route('shop.index'),
            'active_title' => __('Create Shop'),
            'button_type'  => 'list',
            'button_title' => __('Shop List'),
            'button_url'   => route('shop.index'),
        ];

        $shop_owners = (new User())->get_shop_owners();
        $shops       = (new Shop())->getAllShopsAssoc();
       
        return view('admin.modules.shop.create', compact('cms_content', 'shop_owners','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ShopRequest $request)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();
            $original = $request->all();
            $shop = (new Shop())->store_shop($request);
            $changed = $shop->getChanges();
            self::activityLog($request, $original, $changed, $shop);
            success_alert('Congratulations! Your shop has been successfully created.');
            DB::commit();
            return redirect()->route('shop.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_STORE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('shop.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
       $cms_content = [
            'module'       => __('Shop'),
            'module_url'   => route('shop.index'),
            'active_title' => __('Shop Details'),
            'button_type'  => 'list',
            'button_title' => __('Shop List'),
            'button_url'   => route('shop.create'),
        ];

        return view('admin.modules.shop.show', compact('shop', 'cms_content'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $cms_content = [
            'module'       => __('Shop'),
            'module_url'   => route('shop.index'),
            'active_title' => __('Edit Shop'),
            'button_type'  => 'list',
            'button_title' => __('Shop List'),
            'button_url'   => route('shop.index'),
        ];

        $shop_owners = (new User())->get_shop_owners();

        return view('admin.modules.shop.edit', compact('shop', 'cms_content', 'shop_owners'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ShopRequest $request, Shop $shop)
    {
        try{
            DB::beginTransaction();
            $original = $shop->toArray();
            (new Shop())->update_shop($request, $shop);
            $changed = $shop->getChanges();
            self::activityLog($request, $original, $changed, $shop);
            success_alert('Congratulations! Your shop has been successfully updated.');
            DB::commit();
            return redirect()->route('shop.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('shop.index');
        }
       
    }
  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        try{
            DB::beginTransaction();
            (new Shop())->delete_shop($shop);
            success_alert('Congratulations! Your shop has been successfully deleted.');
            DB::commit();
            return redirect()->route('shop.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('shop.index');
        }
    }
}

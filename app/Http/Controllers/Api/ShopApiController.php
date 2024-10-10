<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ShopResource;
use App\Models\Traits\AppActivityLog;
use App\Http\Requests\ShopStoreRequest;
use App\Http\Requests\ShopUpdateRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;

class ShopApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'shop';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            DB::beginTransaction();
            $shops = (new Shop())->get_all_shops_by_auth_user($request);
            $this->status_message = 'Shops fetched successfully.';
            $this->data           = ShopResource::collection($shops);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_FETCH_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
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


    public function store(ShopStoreRequest $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $original = $request->all();
            $shop = (new Shop())->store_shop($request);
            $changed = $shop->getChanges();
            self::activityLog($request, $original, $changed, $shop);
            $this->status_message = 'Congratulations! Your Shop Has Been Successfully Created.';
            $this->data = new ShopResource($shop);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_STORE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }



    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        try {
            DB::beginTransaction();

            $shop = $shop->load(['photo', 'created_by', 'updated_by']);
            $this->status_message = 'Shop details For ' . $shop->name;
            $this->data           = new ShopResource($shop);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_SHOW_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
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
    public function update(ShopUpdateRequest $request, Shop $shop)
    {
        try {
            DB::beginTransaction();
            $original = $shop->toArray();
            $shop->update_shop($request, $shop);
            $changed = $shop->getChanges();
            self::activityLog($request, $original, $changed, $shop);
            $this->status_message = 'Congratulations! Your shop has been successfully updated.';
            $this->data           = new ShopResource($shop);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_UPDATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        try {
            DB::beginTransaction();
            // $shop->delete();
            (new Shop())->delete_shop($shop);
            $this->status_message = 'Your shop has been successfully deleted.';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('SHOP_DELETE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();

    }


    public function get_assigned_shop()
    {
        try {
        DB::beginTransaction();
        $shops = (new Shop())->get_assigned_shops();
        $this->data = ShopResource::collection($shops);
        $this->status_message = 'Your shop has been successfully fetched.';
        DB::commit();
    } catch (Throwable $throwable) {
        DB::rollBack();
        app_error_log('ASSIGNED_SHOP_FETCHED_FAILED', $throwable, 'error');
        $this->status_message = 'Failed! ' . $throwable->getMessage();
        $this->status_code    = $this->status_code_failed;
        $this->status         = false;
    }
        return $this->commonApiResponse();
    }


}

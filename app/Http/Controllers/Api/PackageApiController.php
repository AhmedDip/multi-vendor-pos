<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Http\Resources\PackageResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Package;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class PackageApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'package';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $package=(new Package())->get_package($request);
            $this->data=PackageResource::collection($package);
            $this->status_message = 'Package data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_fetch_FAILED', $throwable, 'error');
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
    public function store(StorePackageRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $package=(new Package())->store_package($request);
            $original=$package->getOriginal();
            $changed=$package->getChanges();
            self::activityLog($request,$original,$changed,$package);
            $this->status_message = 'Package data Create successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_CREATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        try{
            DB::beginTransaction();
            $this->data=new PackageResource($package);
            $this->status_message = 'Package data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_fetch_FAILED', $throwable, 'error');
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
    public function update(UpdatePackageRequest $request, Package $package)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original=$package->getOriginal();
            (new Package())->update_package($request,$package);
            $changed=$package->getChanges();
            self::activityLog($request,$original,$changed,$package);
            $this->status_message = 'Package data update successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package,Request $request)
    {
        try {
            DB::beginTransaction();
            (new Package())->delete_package($package);
            $original=$package->getOriginal();
            $changed=$package->getChanges();
            self::activityLog($request,$original,$changed,$package);
            $this->status_message = 'Package deleted successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('package_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

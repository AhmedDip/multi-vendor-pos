<?php

namespace App\Http\Controllers\api;

use Throwable;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Warehouse;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;

class ProductDependancyApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'product-dependancy';
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $status = Product::getFormattedStatusList();
            $type = Product::getFormattedTypeList();
            $brand=(new Brand())->get_brand_assoc_for_api($request);
            $category=(new Category())->get_category_assoc_for_api($request);
            $warehouse = (new Warehouse())->get_warehouse_assoc_for_api($request);
            $manufacturer = (new Manufacturer())->get_manufacturer_assoc_for_api($request);
            $attribute=(new Attribute())->get_attribute_assoc_for_api($request);
            $this->data=[
                'status'=>$status,
                'type'=>$type,
                'brand'=>$brand,
                'category'=>$category,
                'warehouse'=>$warehouse,
                'manufacturer'=>$manufacturer,
                'attribute'=>$attribute,
            ];
            $this->status_message = 'Product dependancy data fetched successfully';
            DB::commit();
        }
        catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Data_FETCH_FAILED',$throwable,'error');
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

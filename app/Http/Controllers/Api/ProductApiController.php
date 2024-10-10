<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductApiRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;

class ProductApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;
    /**
     * Display a listing of the resource.
     */
    public static string $route = 'product';

    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $products = (new Product())->get_products($request);
            $paginationData = PaginationHelper::generatePaginationData($products);
            $columns = [
                'name'           => 'Name',
                'slug'           => 'Slug',
                'sku'            => 'SKU',
                'status'         => 'Status',
                'price'          => 'Price',
                'discount_price' => 'Discount Price',
                'category_id'    => 'Category',
                'brand_id'       => 'Brand',
                'stock'          => 'Stock',
                'sort_order'     => 'Sort Order',
            ];
            $this->status_message = 'Products fetched successfully.';
            $this->data = [
                'products'   => ProductResource::collection($products),
                'pagination' => $paginationData,
                'columns'    => $columns,
            ];
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_INDEX_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
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
    public function store(ProductApiRequest $request)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();
            $original = $request->all();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $product = (new Product())->store_product($request);
            $changed = $product->getChanges();
            self::activityLog($request, $original, $changed, $product);
            $this->status_message = 'Product created successfully.';
            $this->data = new ProductResource($product);
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_STORE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */

    public function show(Product $product)
    {
        try{
            DB::beginTransaction();
            $product = $product->load(['photo', 'category']);
            $this->status_message = 'Product details for ' . $product->name;
            $this->data = new ProductResource($product);
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_SHOW_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
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
    public function update(Request $request, Product $product)
    {
        try{
            DB::beginTransaction();
            $original = $product->getOriginal();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            (new Product())->update_product($request, $product);
            $changed = $product->getChanges();
            self::activityLog($request, $original, $changed, $product);
            $this->status_message = 'Product Updated Successfully.';
            $this->data = new ProductResource($product);
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_UPDATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product,Request $request)
    {
        try{
            DB::beginTransaction();
            $original = $product->getOriginal();
            (new Product())->destroy_product($product);
            $changed = $product->getChanges();
            self::activityLog($request, $original, $changed, $product);
            $this->status_message = 'Product deleted successfully.';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_DELETED_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();

        
    }

    public function get_services_data(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $products = (new Product())->get_services_data($request);
            $this->status_message = 'Service Fetched successfully.';
            $this->data = [
                'products'   => $products,
            ];
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_INDEX_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }

    
}

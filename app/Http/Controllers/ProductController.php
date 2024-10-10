<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\Warehouse;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use App\Models\AttributeValue;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;
use App\Models\Traits\AppActivityLog;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CommonResponse, AppActivityLog, AccessControlTrait;


    public function index(Request $request)
    {
        $cms_content = [
            'module'       => __('Product'),
            'module_url'   => route('product.index'),
            'active_title' => __('Product List'),
            'button_type'  => 'create',
            'button_title' => __('Create Product'),
            'button_url'   => route('product.create'),
        ];

        $products = (new Product())->get_products($request);
        $brands     = (new Brand())->get_brand_assoc();
        $shops      = (new Shop())->get_shops_assoc();

        $columns = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'price'      => 'Price',
            'stock'      => 'Stock',
        ];

        $search  = $request->all();

        return view('admin.modules.product.index', compact('products', 'cms_content', 'brands','shops','columns','search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cms_content = [
            'module'       => __('Product'),
            'module_url'   => route('product.index'),
            'active_title' => __('Create Product'),
            'button_type'  => 'list',
            'button_title' => __('Product List'),
            'button_url'   => route('product.index'),
        ];

        $shops      = (new Shop())->get_shops_assoc();
        $brands     = (new Brand())->get_brand_assoc();
        $attributes = (new Attribute())->get_attribute_assoc();
        // dd($attributes);
        $values     = (new AttributeValue())->get_attribute_value_assoc();
        $categories = (new Category())->get_category_group_by_shop();
        $warehouses = (new Warehouse())->get_warehouse_assoc_by_shop();
        $manufacturers = (new Manufacturer())->get_manufacturer_assoc_by_shop();

        return view('admin.modules.product.create', compact('cms_content','categories','brands','attributes','values','shops', 'warehouses', 'manufacturers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();
            $original = $request->all();
            $product = (new Product())->store_product($request);
            $changed = $product->getChanges();
            self::activityLog($request, $original,  $changed, $product);
            DB::commit();
            success_alert('Product Created Successfully');
            return redirect()->route('product.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_CREATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('product.index');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $cms_content = [
            'module'       => __('Product'),
            'module_url'   => route('product.index'),
            'active_title' => __('Product Details'),
            'button_type'  => 'list',
            'button_title' => __('Product List'),
            'button_url'   => route('product.index'),
        ];

        return view('admin.modules.product.show', compact('product', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('attributeValues.attribute');
        // dd($product);
        $cms_content = [
            'module'       => __('Product'),
            'module_url'   => route('product.index'),
            'active_title' => __('Edit Product'),
            'button_type'  => 'list',
            'button_title' => __('Product List'),
            'button_url'   => route('product.index'),
        ];

        $shops         = (new Shop())->get_shops_assoc();
        $brands        = (new Brand())->get_brand_assoc();
        $attributes    = (new Attribute())->get_attribute_assoc();
        $values        = (new AttributeValue())->get_attribute_value_assoc();
        $categories    = (new Category())->get_category_group_by_shop();
        $warehouses    = (new Warehouse())->get_warehouse_assoc_by_shop();
        $manufacturers = (new Manufacturer())->get_manufacturer_assoc_by_shop();
        // dd($product);


        return view('admin.modules.product.edit', compact('product', 'cms_content', 'categories', 'brands', 'attributes', 'values', 'shops', 'warehouses', 'manufacturers'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try{
            DB::beginTransaction();
            $original = $product->toArray();
            $product = (new Product())->update_product($request, $product);
            $changed = $product->getChanges();
            self::activityLog($request, $original,  $changed, $product);
            DB::commit();
            success_alert('Product Updated Successfully');
            return redirect()->route('product.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('product.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try{
            DB::beginTransaction();
            $product = (new Product())->destroy_product($product);
            DB::commit();
            success_alert('Product Deleted Successfully');
            return redirect()->route('product.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PRODUCT_DELETED_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->route('product.index');
        }
    }

    public function inventoy(Request $request)
    {
        $cms_content = [
            'module'       => __('Product'),
            'module_url'   => route('product.index'),
            'active_title' => __('Inventory'),

        ];
        $inventories=(new Product())->get_inventory($request);
        $search     = $request->all();
        $columns = [
            'name'       => 'Name',
            'sku'        => 'sku',
            'price'      => 'Price',
            'stock'      => 'Stock',
            'sold'       =>'Total sold'
        ];
        return view('admin.modules.inventory.index',compact('inventories','search','columns', 'cms_content'));

    }

    public function export(Request $request)
    {
        $products = (new Product())->get_products($request);
        $csvFileName = 'Product.csv';
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $handle = fopen($csvFileName, 'w+');
        fputcsv($handle, ['id','Product Name','Slug','Sku','Description','Category','Brand','Price','Discount Price','Stock','Shop Name','Duration','Expiry Date','Type','slot','Attribute']);


        foreach ($products as $product) {

            $attributes = $product->attributeValues ? $product->attributeValues->pluck('name')->implode(', ') : '';

            fputcsv($handle, [
                $product->id,
                $product->name,
                $product->slug,
                $product->sku,
                $product->description,
                $product->category?->name,
                $product->brand?->name,
                $product->price,
                $product->discount_price,
                $product->stock,
                $product?->shop?->name,
                $product->duration,
                $product->expiry_date,
                $product->type === 1 ? 'Product' : 'Service',
                $product->slot,
                $attributes


            ]);
        }

        fclose($handle);

        return response()->download($csvFileName, $csvFileName, $headers);
    }

    public function exportPDF(Request $request){
        $products = (new Product())->get_products($request);

        $data = [
            'products' => $products,
            'title'    =>'Products list'
        ];
        // dd($data);
        $pdf = PDF::loadView('admin.modules.product.productPdf',$data);
        return $pdf->download('products.pdf');
    }

    public function exportInventoryCsv(Request $request)
    {
        $products = (new Product())->get_inventory($request);
        $csvFileName = 'Inventory.csv';
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $handle = fopen($csvFileName, 'w+');
        fputcsv($handle, ['id','Product Name','Slug','Sku','Price','Discount Price','Stock','Total Sold','Expiry Date']);


        foreach ($products as $product) {
            fputcsv($handle, [
                $product->id,
                $product->name,
                $product->slug,
                $product->sku,
                $product->price,
                $product->discount_price,
                $product->stock,
                $product?->sold,
                $product->expiry_date,
            ]);
        }

        fclose($handle);
        return response()->download($csvFileName, $csvFileName, $headers);
    }

    public function exportInventoryPDF(Request $request){
        $inventories= (new Product())->get_inventory($request);

        $data = [
            'inventories' => $inventories,
            'title'    =>'Inventories list'
        ];
        $pdf = PDF::loadView('admin.modules.inventory.inventoryPdf',$data);
        return $pdf->download('Inventory.pdf');
    }
}

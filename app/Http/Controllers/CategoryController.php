<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Observers\CategoryObserver;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Manager\AccessControl\AccessControlTrait;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(CategoryObserver::class)]
class CategoryController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'category';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('category'),
            'module_url'  => route('category.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Category Create'),
            'button_url' => route('category.create'),
        ];
        $categories   = (new Category())->get_category($request);
        $search      = $request->all();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        $shops = (new Shop())->get_shops_assoc();

        return view('admin.modules.category.index',
        compact('cms_content', 'categories', 'search', 'columns', 'shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('category'),
            'module_url'  => route('category.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Category List'),
            'button_url' => route('category.index'),
        ];

        $shops  = (new Shop())->get_shops_assoc();

        return view('admin.modules.category.create', compact('cms_content', 'shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreCategoryRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $category = (new Category())->store_category($request);
           $original = $category->getOriginal();
           $changed = $category->getChanges();
           self::activityLog($request, $original, $changed, $category);
           success_alert(__('category created successfully'));
           DB::commit();
           return redirect()->route('category.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('category_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Category $category):View
    {
        $cms_content = [
            'module' => __('category'),
            'module_url'  => route('category.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Category List'),
            'button_url' => route('category.index'),
        ];
        $category->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.category.show',
                   compact('category', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Category $category):View
    {
        $cms_content = [
            'module' => __('Category'),
            'module_url'  => route('category.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Category List'),
            'button_url' => route('category.index'),
        ];

        $shops  = (new Shop())->get_shops_assoc();
        
        return view('admin.modules.category.edit', compact(
                    'cms_content',
                    'category',
                    'shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateCategoryRequest $request, Category $category):RedirectResponse
    {
        // dd('fdfd');
        // dd($category);
        try {
            DB::beginTransaction();
            $original = $category->getOriginal();
            (new Category())->update_category($request, $category);
            $changed  = $category->getChanges();
            self::activityLog($request, $original, $changed, $category);
            DB::commit();
            success_alert(__('category updated successfully'));
            return redirect()->route('category.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('category_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Category $category):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $category->getOriginal();
            (new Category())->delete_category($category);
            $changed = $category->getChanges();
            self::activityLog($request, $original, $changed, $category);
            DB::commit();
            success_alert(__('category deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('category_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }



    public function export(Request $request)
    {
        $categories  = (new Category())->get_category($request);
        $csvFileName = 'Category.csv';
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $handle = fopen($csvFileName, 'w+');
        fputcsv($handle, ['id','Category Name','Slug','Shop Name','Description']);
        foreach ($categories as $category) {
            fputcsv($handle, [
                $category->id,
                $category->name,
                $category->slug,
                $category?->shop?->name,
                $category->description
            ]);
        }

        fclose($handle);
        return response()->download($csvFileName, $csvFileName, $headers);
    }


    public function exportPDF(Request $request){
        $categories   = (new Category())->get_category($request);

        // dd($categories);
        $data = [
            'categories' => $categories,
            'title'      =>'Categories list'
        ];
        // dd($data);
        $pdf = PDF::loadView('admin.modules.category.categoryPdf',$data);
        return $pdf->download('categories.pdf');
    }


}




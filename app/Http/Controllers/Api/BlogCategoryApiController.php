<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCategoryRequest;
use App\Http\Requests\UpdateBlogCategoryRequest;
use App\Http\Resources\BlogCategoryResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\BlogCategory;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class BlogCategoryApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'blog-category';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $blog_category=(new BlogCategory())->get_blog_category_for_api($request);
            $this->data=BlogCategoryResource::collection($blog_category);
            $this->status_message = 'BlogCategory data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('blog_category_fetch_FAILED', $throwable, 'error');
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
    public function store(StoreBlogCategoryRequest $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $blog_category=(new BlogCategory())->store_blog_category($request);
            $original=$blog_category->getOriginal();
            $changed=$blog_category->getChanges();
            self::activityLog($request,$original,$changed,$blog_category);
            $this->status_message = 'BlogCategory data Create successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('blog_category_CREATE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogCategory $blog_category)
    {
        try{
            DB::beginTransaction();
            $this->data=new BlogCategoryResource($blog_category);
            $this->status_message = 'BlogCategory data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('blog_category_fetch_FAILED', $throwable, 'error');
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
    public function update(UpdateBlogCategoryRequest $request, BlogCategory $blog_category)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original=$blog_category->getOriginal();
            (new BlogCategory())->update_blog_category($request,$blog_category);
            $changed=$blog_category->getChanges();
            self::activityLog($request,$original,$changed,$blog_category); 
            $this->status_message = 'BlogCategory data update successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('blog_category_update_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogCategory $blog_category)
    {
        try {
            DB::beginTransaction();
            (new BlogCategory())->delete_blog_category($blog_category);
            $this->status_message = 'BlogCategory deleted successfully';
            DB::commit();
        } 
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('blog_category_delete_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

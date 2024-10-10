<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiscountRequest;
use App\Http\Requests\UpdateDiscountRequest;
use App\Http\Resources\DiscountResource;
use App\Manager\API\Traits\CommonResponse;
use App\Manager\AccessControl\AccessControlTrait;
use App\Models\Discount;
use App\Models\Shop;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class DiscountController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'discount';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('discount'),
            'module_url'  => route('discount.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Discount Create'),
            'button_url' => route('discount.create'),
        ];
        $discounts   = (new Discount())->get_discount($request);
        $search      = $request->all();
        $shops      = (new Shop())->get_shops_assoc();
        $columns     = [
            'name'       => 'Name',
            'sort_order' => 'Sort Order',
            'status'     => 'Status',
            ];
        return view('admin.modules.discount.index',
        compact('cms_content', 'discounts', 'search', 'columns','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('discount'),
            'module_url'  => route('discount.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Discount List'),
            'button_url' => route('discount.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.discount.create', compact('cms_content','shops'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreDiscountRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $discount = (new Discount())->store_discount($request);
           $original = $discount->getOriginal();
           $changed = $discount->getChanges();
           self::activityLog($request, $original, $changed, $discount);
           success_alert(__('discount created successfully'));
           DB::commit();
           return redirect()->route('discount.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('discount_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Discount $discount):View
    {
        $cms_content = [
            'module' => __('discount'),
            'module_url'  => route('discount.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Discount List'),
            'button_url' => route('discount.index'),
        ];
        $discount->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.discount.show',
                   compact('discount', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Discount $discount):View
    {
        $cms_content = [
            'module' => __('discount'),
            'module_url'  => route('discount.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Discount List'),
            'button_url' => route('discount.index'),
        ];
        $shops      = (new Shop())->get_shops_assoc();
        return view('admin.modules.discount.edit', compact(
                    'cms_content',
                    'discount','shops'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateDiscountRequest $request, Discount $discount):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $discount->getOriginal();
            (new Discount())->update_discount($request, $discount);
            $changed = $discount->getChanges();
            self::activityLog($request, $original, $changed, $discount);
            DB::commit();
            success_alert(__('discount updated successfully'));
            return redirect()->route('discount.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Discount $discount):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $discount->getOriginal();
            (new Discount())->delete_discount($discount);
            $changed = $discount->getChanges();
            self::activityLog($request, $original, $changed, $discount);
            DB::commit();
            success_alert(__('discount deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('discount_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }

    // final public function get_discount_api(){
    //     try{
    //         DB::beginTransaction();
    //         $discount=(new Discount())->get_discount_for_api();
    //         $this->data=DiscountResource::collection($discount);
    //         $this->status_message='Discount fetched successfully';
    //         DB::commit();
    //     }
    //     catch(Throwable $throwable){
    //         DB::rollBack();
    //         app_error_log('Discount data fetch failed',$throwable,'error');
    //         $this->status_message = 'Failed! ' . $throwable->getMessage();
    //         $this->status_code    = $this->status_code_failed;
    //         $this->status         = false;
    //     }
    //     return $this->commonApiResponse();
    // }
}

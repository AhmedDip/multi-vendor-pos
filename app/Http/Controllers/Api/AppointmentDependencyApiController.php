<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\API\Traits\CommonResponse;
use App\Models\Category;
use App\Models\Product;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AppointmentDependencyApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'appointment-dependency';
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $category      = (new Category())->get_category_assoc_for_api($request);
            $getCategoryId = $category->pluck('id')->toArray();
            $services      = (new Product())
                                ->whereIn('category_id', $getCategoryId)
                                ->where('status',Product::STATUS_ACTIVE)
                                ->where('type',Product::TYPE_SERVICE)
                                ->select('id','name')
                                ->get() ;

            $this->data = [
                'category' => $category,
                'products' => $services,
                ];
            $this->status_message = 'Appointment Dependency data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Appointment_Dependency_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();


    }

}

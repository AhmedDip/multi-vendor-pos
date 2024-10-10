<?php

namespace App\Http\Controllers\api;

use App\Helpers\PaginationHelper;
use Throwable;
use App\Models\Expense;
use Illuminate\Http\Request;

use App\Models\membershipCardType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Http\Resources\ExpensesResource;
use App\Http\Requests\StoreExpenseRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\UpdateExpenseRequest;

class ExpenseApiController extends Controller
{
    use CommonResponse, AppActivityLog;

    public static string $route = 'expense';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $expenses   = (new Expense())->get_expense($request);

            $this->data =  ExpensesResource::collection($expenses)->response()->getData();

            $this->status_message = 'Expense data fetched successfully';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Expense_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }


    final public function store(StoreExpenseRequest $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            //    $request->merge(['shop_id' => $request->header('shop-id')]);
            $expense  = (new Expense())->store_expense($request);
            $original = $expense->getOriginal();
            $changed  = $expense->getChanges();
            self::activityLog($request, $original, $changed, $expense);
            $this->status_message = ('Expense created successfully');
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Expense_data_Create_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    final public function show(Expense $expense)
    {
        try {
            DB::beginTransaction();

            $this->data = new ExpensesResource($expense);
            
            $this->status_message = 'Expense data fetched successfully';
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Expense_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }


    final public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        try {
            DB::beginTransaction();

            $original = $expense->getOriginal();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original = $expense->getOriginal();
            (new Expense())->update_expense($request, $expense);
            $changed  = $expense->getChanges();
            self::activityLog($request, $original, $changed, $expense);

            DB::commit();
            $this->status_message = 'Expense data Update successfully';;
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Expense_data_Update_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }


    final public function destroy(Request $request, Expense $expense)
    {
        try {
            DB::beginTransaction();
            (new Expense())->delete_expense($expense);
            $original = $expense->getOriginal();
            $changed  = $expense->getChanges();
            self::activityLog($request, $original, $changed, $expense);
            DB::commit();
            $this->status_message = 'Expense data Delete successfully';
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Expense_data_DELETE_FAILED_API', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return $this->commonApiResponse();
    }
}

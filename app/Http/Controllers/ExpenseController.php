<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Expense;
use Illuminate\View\View;
use Illuminate\Http\Request;
// use App\Manager\API\Traits\AppActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreExpenseRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\UpdateExpenseRequest;
use App\Manager\AccessControl\AccessControlTrait;

class ExpenseController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'expense';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request):View
    {
        $cms_content = [
            'module'        => __('Expense'),
            'module_url'  => route('expense.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title'  => __('Expense Create'),
            'button_url' => route('expense.create'),
        ];
        $expenses   = (new Expense())->get_expense($request);
        $search      = $request->all();
        $columns     = [
            'purpose'  => 'Purpose',
            'amount'   => 'Amount',
            'date'     => 'Date',
            'user_id'  => 'Created By',
            'shop_id'  => 'Shop'

            ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.expense.index',
        compact('cms_content', 'expenses', 'search', 'columns','shop'));
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create():View
    {
        $cms_content = [
            'module' => __('Expense'),
            'module_url'  => route('expense.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title'  => __('Expense List'),
            'button_url' => route('expense.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.expense.create', compact('cms_content','shop'));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreExpenseRequest $request):RedirectResponse
    {
        try {
           DB::beginTransaction();
           $expense = (new Expense())->store_expense($request);
           $original = $expense->getOriginal();
           $changed = $expense->getChanges();
           self::activityLog($request, $original, $changed, $expense);
           success_alert(__('expense created successfully'));
           DB::commit();
           return redirect()->route('expense.index');
       } catch (Throwable $throwable) {
           DB::rollBack();
           app_error_log('expense_CREATE_FAILED', $throwable, 'error');
           failed_alert($throwable->getMessage());
           return redirect()->back();
       }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Expense $expense):View
    {
        $cms_content = [
            'module' => __('Expense'),
            'module_url'  => route('expense.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Expense List'),
            'button_url' => route('expense.index'),
        ];
        $expense->load(['activity_logs', 'created_by', 'updated_by']);

        return view('admin.modules.expense.show',
                   compact('expense', 'cms_content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Expense $expense):View
    {
        $cms_content = [
            'module' => __('Expense'),
            'module_url'  => route('expense.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title'  => __('Expense List'),
            'button_url' => route('expense.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        return view('admin.modules.expense.edit', compact(
                    'cms_content',
                    'expense','shop'
                ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateExpenseRequest $request, Expense $expense):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $expense->getOriginal();
            (new Expense())->update_expense($request, $expense);
            $changed = $expense->getChanges();
            self::activityLog($request, $original, $changed, $expense);
            DB::commit();
            success_alert(__('expense updated successfully'));
            return redirect()->route('expense.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('expense_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Expense $expense):RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $expense->getOriginal();
            (new Expense())->delete_expense($expense);
            $changed = $expense->getChanges();
            self::activityLog($request, $original, $changed, $expense);
            DB::commit();
            success_alert(__('expense deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('expense_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            }
        return redirect()->back();
    }
}

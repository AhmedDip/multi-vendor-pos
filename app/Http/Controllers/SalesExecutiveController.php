<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesExecutiveRequest;
use App\Models\RoleExtended;
use Throwable;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Traits\AppActivityLog;
use App\Manager\AccessControl\AccessControlTrait;

class SalesExecutiveController extends Controller
{
    use AppActivityLog, AccessControlTrait;

    public static string $route = 'sales-executive';
    /**
     * Display a listing of the resource.
     */

    
    public function index(Request $request){

        $cms_content =[
            'module'       => __('Sales Executive'),
            'module_url'   => route('sales-executive.index'),
            'active_title' => __('List'),
            'button_type'  => 'create',
            'button_title' => __('Create Sales Executive'),
            'button_url'   => route('sales-executive.create'),
        ];

        $request->merge([
            'order_by_column' => $request->input('order_by_column', 'id'),
            'order_by'        => $request->input('order_by', 'desc'),
        ]);

        $sales_executives = (new User())->get_sales_executives($request);
        // dd($sales_executives);

        $columns = [
            // 'id'          => 'ID',
            'name'        => 'Name',
            'email'       => 'Email',
            'phone'       => 'Phone',
            'status'      => 'Status',
        ];

        $search = $request->all();
        $shops = (new Shop())->getAllShopsAssoc();

        return view('admin.modules.sales-executive.index',
            compact('cms_content', 'sales_executives', 'search', 'columns','shops')
        );
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cms_content = [
            'module'       => __('Sales Executive'),
            'module_url'   => route('sales-executive.index'),
            'active_title' => __('Create'),
            'button_type'  => 'back',
            'button_title' => __('Back'),
            'button_url'   => route('sales-executive.index'),
        ];

        $shops = (new Shop())->getAllShopsAssoc();

        $emp_roles = (new User())->get_emp_roles();


        $selected_shop = [];
        $selected_roles = [];
        

        return view('admin.modules.sales-executive.create', compact('cms_content','shops','selected_shop','emp_roles','selected_roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalesExecutiveRequest $request)
    {
        // dd($request->all());
        try{
            DB::beginTransaction();
            $original = $request->all();
            $sales_executive = (new User())->store_sales_executive($request);
            $changed         = $sales_executive->getChanges();
            self::activityLog($request, $original, $changed, $sales_executive);
            success_alert('Congratulations! Sales Executive has been successfully created.');
            DB::commit();
            return redirect()->route('sales-executive.index');
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_STORE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $sales_executive)
    {
        $cms_content = [
            'module'       => __('Sales Executive'),
            'module_url'   => route('sales-executive.index'),
            'active_title' => __('View'),
            'button_type'  => 'back',
            'button_title' => __('Back'),
            'button_url'   => route('sales-executive.index'),
        ];

        return view('admin.modules.sales-executive.show', compact('cms_content', 'sales_executive'));
    }
 

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $sales_executive)
    {
        $cms_content = [
            'module'       => __('Sales Executive'),
            'module_url'   => route('sales-executive.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'back',
            'button_title' => __('Back'),
            'button_url'   => route('sales-executive.index'),
        ];

        $shops          = (new Shop())->getAllShopsAssoc();
        $selected_shop  = $sales_executive->shops->pluck('id')->toArray();
        $emp_roles      = (new User())->get_emp_roles();
        $selected_roles = $sales_executive->roles->pluck('id')->toArray();

        return view('admin.modules.sales-executive.edit', compact('cms_content', 'sales_executive', 'shops', 'selected_shop','emp_roles','selected_roles'));
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $sales_executive)
    {
        try{
            DB::beginTransaction();
            $original = $sales_executive->toArray();
            $sales_executive = (new User())->update_sales_executive($request, $sales_executive);
            $changed         = $sales_executive->getChanges();
            self::activityLog($request, $original, $changed, $sales_executive);
            success_alert('Congratulations! Sales Executive has been successfully updated.');
            DB::commit();
            return redirect()->route('sales-executive.index');
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }
  

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $sales_executive)
    {
        try{
            DB::beginTransaction();
            $sales_executive = (new User())->delete_user($sales_executive);
           
            success_alert('Congratulations! Sales Executive has been successfully deleted.');
            DB::commit();
            return redirect()->route('sales-executive.index');
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('SALES_EXECUTIVE_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }
}

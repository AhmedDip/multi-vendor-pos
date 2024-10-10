<?php

namespace App\Http\Controllers\Api;

use Exception;
use Throwable;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Traits\AppActivityLog;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Resources\AppointmentResource;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Manager\AccessControl\AccessControlTrait;
use App\Http\Resources\AppointmentTransactionDetailsResource;

class AppointmentApiController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'appointment';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $appointments = (new Appointment())->get_appointment($request, false);
           
            $columns     = [
                'name'                    => 'Name',
                'email'                   => 'Email',
                'phone'                   => 'Phone',
                'message'                 => 'Message',
                'category_id'             => 'Category',
                'product_id'              => 'Service name',
                'date'                    => 'Date',
                'shop_id'                 => 'Shop Id',
            ];
            $this->data = [
                'appointment' => AppointmentResource::collection($appointments)->response()->getData(),
                'columns'     => $columns
                ];
                $this->status_message = 'Appointment data fetched successfully';
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Appointment_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();


    }
    final public function store(StoreAppointmentRequest $request)
    {
        // dd($request->all());
        try {
           DB::beginTransaction();
           
        //    $request->merge(['shop_id' => $request->header('shop-id')]);
           $appointment = (new Appointment())->store_appointment($request);
        
           $original    = $appointment->getOriginal();
           $changed     = $appointment->getChanges();
           self::activityLog($request,$original,$changed,$appointment);
        //    (new Order())->convertAppointmentToOrder($appointment, $request);
           $this->status_message = ('Appointment created successfully');
           $this->data = (new AppointmentResource($appointment));
           DB::commit();
       } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Appointment_data_Create_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
       return $this->commonApiResponse();
    }

    final public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        try {
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $original = $appointment->getOriginal();
            (new Appointment())->update_appointment($request, $appointment);
            $changed = $appointment->getChanges();
            self::activityLog($request,$original,$changed,$appointment);

            $this->data = (new AppointmentResource($appointment));
       
            DB::commit();
            $this->status_message = 'Appointment data Update successfully';;
        } catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Appointment_data_Update_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }



    final public function show(Appointment $appointment){
        try{
            DB::beginTransaction();
            
            $columns     = [
                'name'                    => 'Name',
                'email'                   => 'Email',
                'phone'                   => 'Phone',
                'message'                 => 'Message',
                'category_id'             => 'Category',
                'product_id'              => 'Service',
                'date'                    => 'Date',
                'shop_id'                 => 'Shop Id',
            ];
            $this->data = [
                'membership_card' => new AppointmentResource($appointment),
               'columns'         => $columns
            ];

            $this->status_message = 'Appointment data fetched successfully';
            DB::commit();
        }catch(Throwable $throwable){
            DB::rollBack();
            app_error_log('Appointment_data_fetched_failed_API', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }    
        return $this->commonApiResponse();
    }

    final public function destroy(Request $request, Appointment $appointment)
    {
        try {
            DB::beginTransaction();
            $original = $appointment->getOriginal();
            (new Appointment())->delete_appointment($appointment);
            $changed = $appointment->getChanges();
            self::activityLog($request, $original, $changed, $appointment);
            DB::commit();
            $this->status_message = 'appointment deleted update successfully';
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('appointment_DELETE_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
            }
        return $this->commonApiResponse();
    }


    public function get_transaction_data(Request $request, $invoice_no)
    {
        try{
            DB::beginTransaction();
            $appointment = (new Appointment())->get_appointment_by_invoice_no($invoice_no);
            $dependancy_data = $appointment->get_dependancy_data($request);
            $this->status_message = 'Order details.';
            $this->data           = [
                'transaction_data' => new AppointmentTransactionDetailsResource($appointment),
                'dependancy_data' => $dependancy_data,
            ];
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('ORDER_SHOW_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function get_appointment_dependency_data(Request $request)
    {
        try{
            DB::beginTransaction();
            // $request->merge(['shop_id' => $request->header('shop-id')]);
            $products = (new Product())->get_services_data($request);
            $categories = (new Category())->get_categories_data($request);
            $this->status_message = 'Appointment dependency data fetched successfully';
            $this->data           = [
                'products' => $products,
                'categories' => $categories,
            ];
            DB::commit();
        }
        catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Appointment_dependency_data_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function get_appointment_by_date(Request $request, $date)
    {
        try {
            DB::beginTransaction();
            $shopId = $request->header('shop-id');

            $appointments = (new Appointment())->fetchAppointmentsByDate($shopId, $date);

            $this->data = AppointmentResource::collection($appointments);
            $this->status_message = 'Appointment data by date fetched successfully';

            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('Appointment_fetch_FAILED', $throwable, 'error');
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
    }

  
}

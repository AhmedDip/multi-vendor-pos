<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\View\View;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use App\Mail\ConfirmAppointmentMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Response;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Manager\AccessControl\AccessControlTrait;

class AppointmentController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'appointment';

    /**
     * Display a listing of the resource.
     */
    final public function index(Request $request): View
    {
        $cms_content = [
            'module'        => __('Appointment'),
            'module_url'    => route('appointment.index'),
            'active_title'  => __('List'),
            'button_type'   => 'create',
            'button_title'  => __('Appointment Create'),
            'button_url'    => route('appointment.create'),
        ];
        $appointments = (new Appointment())->get_appointment($request, true);

        $search       = $request->all();
        $columns      = [
            'name'         => 'Name',
            'email'        => 'Email',
            'phone'        => 'Phone',
            'message'      => 'Message',
            'category_id'  => 'Category',
            'product_id'   => 'Product',
            'date'         => 'Date',
            'shop_id'      => 'Shop Id',
        ];


        $shop     = (new Shop)->getAllShopsAssoc();
        $category = (new Category)->getAllcategory();

        return view('admin.modules.appointment.index',
            compact(
                'cms_content',
                'appointments',
                'search',
                'columns',
                'shop',
                'category'
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    final public function create(Request $request): View
    {
        $cms_content = [
            'module'       => __('Appointment'),
            'module_url'   => route('appointment.index'),
            'active_title' => __('Create'),
            'button_type'  => 'list',
            'button_title' => __('Appointment List'),
            'button_url'   => route('appointment.index'),
        ];
        $shop          = (new Shop)->getAllShopsAssoc();
        $category_type = (new Category())->get_category_group_by_shop();
        $services_type = (new Product())->get_product_type_service_and_group_by_category();

        // dd($services_type);

        return view('admin.modules.appointment.create', compact(
            'cms_content',
            'shop',
            'category_type',
            'services_type'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    final public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $appointment = (new Appointment())->store_appointment($request);
            $original = $appointment->getOriginal();
            $changed = $appointment->getChanges();
            self::activityLog($request, $original, $changed, $appointment);
            success_alert(__('appointment created successfully'));
            DB::commit();
            $email_data = [
                'name' => $appointment->name,
                'date' => Carbon::parse($appointment->date)->format('d m Y'),
                'shop' => $appointment->get_shop?->name,
            ];
            // dd($email_data);
            Mail::to($appointment['email'])->send(new ConfirmAppointmentMail($email_data));
            return redirect()->route('appointment.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('appointment_CREATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     */
    final public function show(Appointment $appointment): View
    {
        $cms_content = [
            'module' => __('Appointment'),
            'module_url'  => route('appointment.index'),
            'active_title' => __('Details'),
            'button_type'  => 'list',
            'button_title'  => __('Appointment List'),
            'button_url' => route('appointment.index'),
        ];
        $appointment->load(['activity_logs', 'created_by', 'updated_by']);

        return view(
            'admin.modules.appointment.show',
            compact('appointment', 'cms_content')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    final public function edit(Appointment $appointment): View
    {
        $cms_content = [
            'module'       => __('Appointment'),
            'module_url'   => route('appointment.index'),
            'active_title' => __('Edit'),
            'button_type'  => 'list',
            'button_title' => __('Appointment List'),
            'button_url'   => route('appointment.index'),
        ];
        $shop = (new Shop)->getAllShopsAssoc();
        $category_type = (new Category())->get_category_group_by_shop();
        $services_type = (new Product())->get_product_type_service_and_group_by_category();

        $selectedServices = $appointment->services->pluck('id')->toArray();

        return view('admin.modules.appointment.edit', compact(
            'cms_content',
            'appointment',
            'shop',
            'category_type',
            'services_type',
            'selectedServices'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    final public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $appointment->getOriginal();
            (new Appointment())->update_appointment($request, $appointment);
            $changed = $appointment->getChanges();
            self::activityLog($request, $original, $changed, $appointment);
            DB::commit();
            success_alert(__('appointment updated successfully'));
            return redirect()->route('appointment.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('appointment_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    final public function destroy(Request $request, Appointment $appointment): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $original = $appointment->getOriginal();
            (new Appointment())->delete_appointment($appointment);
            $changed = $appointment->getChanges();
            self::activityLog($request, $original, $changed, $appointment);
            DB::commit();
            success_alert(__('appointment deleted successfully'));
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('appointment_DELETE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
        }
        return redirect()->back();
    }




    public function showPaymentForm($appointmentId)
    {
        $cms_content = [
            'module' => __('Appointment'),
            'module_url' => route('appointment.index'),
            'active_title' => __('Payment'),
            'button_type' => 'list',
            'button_title' => __('Appointment List'),
            'button_url' => route('appointment.index'),
        ];

        $appointment = Appointment::findOrFail($appointmentId);
        $customer = $appointment->customer;

        $discount_percentage = $customer ? $customer->getDiscount() : 0;

        $products = $appointment->services;
        $totalAmount = $products->sum('price');

        $payment_methods = (new PaymentMethod())->get_payment_method_assoc();

        return view('admin.modules.appointment.pay', compact(
            'appointment',
            'totalAmount',
            'payment_methods',
            'cms_content',
            'discount_percentage',
        ));
    }



    public function processPaymentForm(Request $request, $appointmentId)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $appointment = Appointment::findOrFail($appointmentId);
            $appointment->update([
                'amount' => $request->amount,
            ]);

            $shopId = $appointment->shop_id ?? $appointment->get_shop->id;
            $request->merge([
                'shop_id'         => $shopId,
                'phone'           => $appointment->phone ?? $appointment->customer->phone,
                'order_date'      => $appointment->date,
                'total_amount'    => $appointment->amount,
                'discount_amount' => $request->discount,
                'note'            => $appointment->message
            ]);

            $order = Order::where('customer_id', $appointment->customer_id)
                ->where('shop_id', $shopId)
                ->first();

            if ($order) {
                $order = (new Order())->update_order_backend($request, $order, $appointment->customer);
            } else {
                $order = (new Order())->store_order_backend($request, $appointment->customer);
            }

            foreach ($appointment->services as $service) {
                OrderItem::updateOrCreate(
                    ['order_id' => $order->id, 'product_id' => $service->id],

                    ['quantity' => 1, 'unit_price' => $service->price, 'total_price' => $service->price]
                );
            }

            $transaction = Transaction::firstOrNew(['appointment_id' => $appointment->id]);
            $transaction->prepare_appointment_data($request, $appointment, $order);
            $transaction->save();


            DB::commit();
            return redirect()->route('appointment.pay', $appointmentId)->with('success', 'Payment details updated successfully.');
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('appointment_PAYMENT_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

    public function getAppointmentsInvoiceForDownloadPdf(Request $request)
    {
        $appointmentIds = $request->input('appointment_ids', []);
        $appointments   = Appointment::with('get_shop', 'customer', 'transaction', 'services')
            ->when(
                !empty($appointmentIds),
                function ($query) use ($appointmentIds) {
                    $query->whereIn('id', $appointmentIds);
                }
            )
            ->get();

        $appointmentData = $appointments->map(function ($appointment) {
            return [
                'name'                => $appointment->name,
                'phone'               => $appointment->phone,
                // 'address'          => $appointment?->customer?->address,
                'payment_type'        => $appointment?->transaction?->payment_type === 1 ? 'Cash' : 'Online Payment',
                'shop_name'           => $appointment?->get_shop?->name,
                'date'                => Carbon::parse($appointment->date)->format('l, d F, Y h:i a'),
                // 'invoice_number'   => $order->invoice_number,
                'total_payable_amount' => $appointment?->transaction?->total_payable_amount,
                'discount'            => $appointment?->transaction?->discount,
            ];
        });

        return response()->json(['appointments' => $appointmentData]);
    }

    public function downloadAppointmentInvoice(Request $request)
    {

        $appointmentIds = $request->query('appointments', []);
        $appointments   = Appointment::with('get_shop', 'customer', 'transaction', 'services')
            ->when(
                !empty($appointmentIds),
                function ($query) use ($appointmentIds) {
                    $query->whereIn('id', explode(',', $appointmentIds));
                }
            )
            ->get();
        $pdf = PDF::loadView('admin.modules.appointment.appointmentInvoicePdf', compact('appointments'));

        return $pdf->download('Appointment_invoice.pdf');
    }

    public function exportAppointmentPDF(Request $request)
    {
        $appointments = (new Appointment())->get_appointment($request);

        $data = [
            'appointments' => $appointments,
            'title'        => 'Appointments list'
        ];
        // dd($data);
        $pdf = PDF::loadView('admin.modules.appointment.appontmentPdfDownload', $data);
        return $pdf->download('Appointments.pdf');
    }




    public function exportAppointmentCSV(Request $request)
    {
        $appointments = (new Appointment())->get_appointment($request);
        $csvFileName = 'Appointment.csv';
        $headers = [
            'Content-Type' => 'text/csv',
        ];

        $handle = fopen($csvFileName, 'w+');
        fputcsv(
            $handle,
            [
                'Invoice Number',
                'Shop Name',
                'Order Date',
                'Customer Name',
                'Customer Phone',
                'Customer Email',
                'Customer Message',
                'Ordered Category',
                'Ordered Services',
                'Total',
                'Discount',
                'Total Payable',
                'Paid Amount',
                'Due Amount',
                // 'Order Status',
                'Payment Status',
                'Payment Type',
                'Sender Number',
                'Transaction Number',
            ]
        );


        foreach ($appointments as $appointment) {

            fputcsv($handle, [
                $appointment->invoice_number,
                $appointment->get_shop?->name,
                $appointment->date,
                $appointment->name,
                $appointment->phone,
                $appointment->email,
                $appointment->message,
                $appointment?->get_category?->name,
                $appointment?->services?->map(function ($service) {
                    return $service->name;
                })->implode(', '),

                number_format($appointment->amount, 2),
                number_format($appointment?->transaction?->discount, 2),
                number_format($appointment?->transaction?->total_payable_amount, 2),
                number_format($appointment?->transaction?->total_paid_amount, 2),
                number_format($appointment?->transaction?->total_due_amount, 2),
                // $appointment->status === 1 ? 'Pending' : 'Completed',
                $appointment?->transaction?->payment_status === 1 ? 'Paid' : 'Unpaid',
                $appointment?->transaction?->payment_type === 1 ? 'Cash' : 'Online Payment',
                $appointment?->transaction?->sender_number,
                $appointment?->transaction?->trx_id,
            ]);
        }

        fclose($handle);

        return response()->download($csvFileName, $csvFileName, $headers);
    }
}

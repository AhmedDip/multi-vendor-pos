<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use App\Manager\Constants\GlobalConstant;
use App\Http\Resources\PaymentMethodResource;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Appointment extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

        public const STATUS_ACTIVE = 1;
        public const STATUS_INACTIVE = 2;

        public const STATUS_LIST = [
            self::STATUS_ACTIVE   => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];

        /**
         * @return BelongsTo
         */
        final public function created_by(): BelongsTo
        {
            return $this->belongsTo(User::class, 'created_by_id', 'id');
        }

        /**
         * @return BelongsTo
         */
        final public function updated_by(): BelongsTo
        {
            return $this->belongsTo(User::class, 'updated_by_id', 'id');
        }

        final public function activity_logs(): MorphMany
        {
            return $this->morphMany(ActivityLog::class, 'logable')->orderByDesc('id');
        }

       /**
         * @return MorphOne
         */
        final public function photo(): MorphOne
        {
            return $this->morphOne(MediaGallery::class, 'imageable');
        }

        /**
         * @return MorphMany
         */
        final public function photos(): MorphMany
        {
            return $this->morphMany(MediaGallery::class, 'imageable');
        }


        private function prepare_data(Request $request, Appointment $appointment = null): array
        {
            $invoiceNumber = Order::generateInvoiceNumber($request->shop_id);

            if ($request->input('phone')) {
                $customer = Customer::query()->where('phone', $request->input('phone'))->where('shop_id', $request->input('shop_id'))->first();
            }
    
            if (!$customer) {
                $customer = (new Customer())->store_customer($request);
            }

            if ($appointment) {
                $data['appointment'] = [
                    'name'                   => $request->input('name') ?? $appointment->name,
                    'email'                  => $request->input('email') ?? $appointment->email,
                    'phone'                  => $request->input('phone') ?? $appointment->phone,
                    'message'                => $request->input('message') ?? $appointment->message,
                    'customer_id'            => $request->input('customer_id') ?? $customer->id,
                    'invoice_number'         => $invoiceNumber ?? $appointment->invoice_number,
                    'category_id'            => $request->input('category_id') ?? $appointment->category_id,
                    'date'                   => Carbon::parse($request->input('date')) ?? $appointment->date,
                    'shop_id'                => $request->input('shop_id') ?? $appointment->shop_id,
                    
                ];
                $data['services'] = $request->input('product_id') ?? $appointment->services->pluck('id')->toArray();
                
            } else {
                $data['appointment'] = [
                    'name'                   => $request->input('name'),
                    'email'                  => $request->input('email'),
                    'phone'                  => $request->input('phone'),
                    'message'                => $request->input('message'),
                    'customer_id'            => $request->input('customer_id') ?? $customer->id,
                    'invoice_number'         => $invoiceNumber,
                    'category_id'            => $request->input('category_id'),
                    'date'                   => Carbon::parse($request->input('date')),
                    'shop_id'                => $request->input('shop_id'),
                    
                ];
                // $data['services'] = $request->input('services') ?? [];
                $data['services'] = $request->input('product_id') ?? [];
            }

            return $data;
        }
        
        final public function get_appointment(Request $request, bool $paginate = true)
        {
            $query = self::query();
            // $query = self::all();
            if($request->input('shop_id')){
                $query->where('shop_id', $request->input('shop_id'));
            }
            if ($request->input('name')) {
                $query->where('name', 'like', '%' . $request->input('name') . '%');
            }
            if ($request->input('phone')) {
                $query->where('phone', 'like', '%' . $request->input('phone') . '%');
            }
            if ($request->input('date')) {
                // $date = date('Y-m-d',strtotime($request->input('date')));
                $query->whereDate('date',date('Y-m-d',strtotime($request->input('date'))));
            }

            if ($request->input('category_id')) {
                $query->where('category_id', $request->input('category_id'));
            }
            
            if ($request->input('order_by_column')) {
                $query->orderBy($request->input('order_by_column'),$request->input('order_by')?? 'DESC');
            }

            if ($paginate) {
                return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
            } else {
                return $query->get();
            }
        }

        public function store_appointment(Request $request){
            $data        = $this->prepare_data($request);
            $appointment = self::query()->create($data['appointment']);
            $appointment->services()->sync($data['services']);


            return $appointment;

        }

        public function update_appointment(Request $request, Appointment $appointment)
        {
            $data = $this->prepare_data($request, $appointment);
            $appointment->update($data['appointment']);
            $appointment->services()->sync($data['services']);

            return true;
        }

        public function delete_appointment(Model|Appointment $appointment)
        {
            $appointment->services()->detach();
            return $appointment->delete();
        }

        public function get_shop():BelongsTo{
            return $this->belongsTo(Shop::class, 'shop_id', 'id');
        }
        public function get_category():BelongsTo{
            return $this->belongsTo(Category::class, 'category_id', 'id');
        }
        // public function get_Product():BelongsTo{
        //     return $this->belongsTo(Product::class, 'product_id', 'id');
        // }

        public function services(): BelongsToMany
        {
            return $this->belongsToMany(Product::class, 'appointment_product');
        }

        // public function getServicesByCategory($categoryId)
        // {
        //     return $this->whereHas('services', function ($query) use ($categoryId) {
        //         $query->where('category_id', $categoryId);
        //     })->get();
        // }
        public function total_appointment($shop_id = null){
            if($shop_id == null){
                // return self::where('status',self::STATUS_ACTIVE)->count();
                return self::count();
            }
            else{
                return self::where('shop_id',$shop_id)->count();
            }
        }

        public function customer(){
            return $this->belongsTo(Customer::class);
        }

        public function transaction()
        {
            return $this->hasOne(Transaction::class);
        }

        public function get_appointment_by_invoice_no($invoice_no){
            return self::where('invoice_number',$invoice_no)->first();
        }

        public function get_dependancy_data(Request $request)
        {
            $paymentTypeList = [];
            foreach (Order::PAYMENT_TYPE_LIST as $key => $value) {
                $paymentTypeList[] = ['id' => $key, 'name' => $value];
            }
        
            $paymentStatusList = [];
            foreach (Order::PAYMENT_STATUS_LIST as $key => $value) {
                $paymentStatusList[] = ['id' => $key, 'name' => $value];
            }
        
            $orderStatusList = [];
            foreach (Order::ORDER_STATUS_LIST as $key => $value) {
                $orderStatusList[] = ['id' => $key, 'name' => $value];
            }
    
            $shop_id = $request->header('shop-id');
    
            $payment_method = (new PaymentMethod())->get_payment_methods($shop_id);
        
            $data = [
                'payment_type'          => $paymentTypeList,
                'payment_methods'       => PaymentMethodResource::collection($payment_method),
                'payment_status'        => $paymentStatusList,
                'order_status'          => $orderStatusList,
            ];
        
            return $data;
        }

        public function fetchAppointmentsByDate($shopId, $date)
        {
            return self::query()
                ->whereDate('date', $date)
                ->where('shop_id', $shopId)
                ->get();
        }


      

}

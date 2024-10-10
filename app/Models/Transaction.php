<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    public const ORDER_STATUS_PENDING   = 1;
    public const ORDER_STATUS_COMPLETED  = 2;
    public const ORDER_STATUS_CANCELED   = 3;

    public const ORDER_STATUS_LIST = [
        self::ORDER_STATUS_PENDING    => 'Pending',
        self::ORDER_STATUS_COMPLETED  => 'Completed',
        self::ORDER_STATUS_CANCELED   => 'Canceled',
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


    public function store_transaction(Request $request, $order)
    {
        return self::query()->create($this->prepare_transaction_data($request, $order));
    }

    public function update_transaction(Request $request, $order)
    {
        return $this->update($this->prepare_transaction_data($request, $order));
    }

    public function prepare_transaction_data(Request $request, $order)
    {
        return [
            'order_id'          => $request->input('order_id') ?? $order->id,
            'payment_method_id' => $request->input('payment_method_id'),
            'amount'            => $request->input('amount'),
            'sender_account'     => $request->input('sender_account') ?? null,
            'trx_id'            => $request->input('trx_id') ?? null,
            'payment_status'    => $request->input('payment_status') ?? Order::PAYMENT_STATUS_SUCCEESS,
        ];
    }



    
    
    
    
    








    public function prepare_appointment_data($request, $appointment, $order)
    {
        $this->appointment_id       = $appointment->id;
        $this->order_id             = $order->id;
        $this->payment_method_id    = $request->payment_method_id;
        $this->total_payable_amount = $request->total_payable_amount ?? $request->amount;
        $this->total_paid_amount    = $request->total_paid_amount;
        $this->total_due_amount     = $request->total_due_amount;
        $this->discount             = $request->discount;
        $this->discount_percentage  = $request->discount_percentage;
        $this->payment_type         = $request->payment_type;
        $this->sender_account        = $request->sender_account;
        $this->trx_id               = $request->trx_id;
        $this->payment_status       = $request->payment_status;
        $this->order_status         = $request->order_status;

        return $this;
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function payment_method()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Manager\Utility\Utility;
use App\Manager\ImageUploadManager;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/shop-photos/';
    public const PHOTO_WIDTH = 600;
    public const PHOTO_HEIGHT = 600;

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



    public function get_shops(Request $request)
    {
        // $query = self::query()->with(['photo', 'created_by', 'updated_by'])->orderBy('id', 'desc');
        $query = self::query()->with(['photo', 'created_by', 'updated_by']);
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        // if ($request->input('slug')) {
        //     $query->where('slug', 'like', '%' . $request->input('slug') . '%');
        // }
        if ($request->input('address')) {
            $query->where('address', 'like', '%' . $request->input('address') . '%');
        }
        if ($request->input('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }
        if ($request->input('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }


        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }



    public function store_shop($request)
    {
        $data = $this->prepare_data($request);
        $shop = self::create($data['shop']);
        $this->upload_photo($request, $shop);
        $this->assignUserToShop($shop, $data['shop']['shop_owner_id']);
        return $shop;
    }


    public function update_shop($request, Shop $shop)
    {
        $data = $this->prepare_data($request, $shop);
        $shop->update($data['shop']);
        $this->upload_photo($request, $shop);
        $this->assignUserToShop($shop, $data['shop']['shop_owner_id']);

        return true;
    }



    public function prepare_data($request, Shop $shop = null): array
    {
        if ($shop) {
            $data['shop'] = [
                'name' => $request->input('name') ?? $shop->name,
                'slug' => $request->input('slug') ?? $shop->slug,
                'description' => $request->input('description') ?? $shop->description,
                'phone' => $request->input('phone') ?? $shop->phone,
                'email' => $request->input('email') ?? $shop->email,
                'address' => $request->input('address') ?? $shop->address,
                'status' => $request->input('status') ?? $shop->status,
                'shop_color' => $request->input('shop_color') ?? $shop->shop_color,
                'shop_owner_id' => $request->input('shop_owner_id') ?? $shop->shop_owner_id,
            ];
        } else {
            $data['shop'] = [
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'address' => $request->input('address'),
                'status' => $request->input('status') ?? self::STATUS_ACTIVE,
                'shop_color' => $request->input('shop_color'),
                'shop_owner_id' => $request->input('shop_owner_id') ?? auth()->id(),
            ];
        }

        return $data;
    }


    private function upload_photo(Request $request, Shop|Model $shop): void
    {
        $file = $request->file('photo');
        if (is_string($request->input('photo'))) {
            $file = Storage::get($request->input('photo'));
        }
        if (!$file) {
            return;
        }
        $photo = (new ImageUploadManager)->file($file)
            ->name(Utility::prepare_name($shop->name))
            ->path(self::PHOTO_UPLOAD_PATH)
            ->auto_size()
            ->watermark(true)
            ->upload();

        $media_data = [
            'photo' => self::PHOTO_UPLOAD_PATH . $photo,
            'type' => null,
            'shop_id' => $request->input('shop_id', null)
        ];
        if ($shop->photo && !empty($shop->photo?->photo)) {
            ImageUploadManager::deletePhoto($shop->photo?->photo);
            $shop->photo->delete();
        }
        $shop->photo()->create($media_data);
    }


    public function get_all_shops_by_auth_user(Request $request)
    {
        $query = self::query()->with(['photo', 'created_by', 'updated_by']);

        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->input('slug')) {
            $query->where('slug', 'like', '%' . $request->input('slug') . '%');
        }

        if ($request->input('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->input('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->where('shop_owner_id', Auth::id())->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));

    }

    public function delete_shop(Shop $shop)
    {
        if ($shop->photo && !empty($shop->photo?->photo)) {
            ImageUploadManager::deletePhoto($shop->photo?->photo);
            $shop->photo->delete();
        }
        return $shop->delete();
    }

    public function getAllShopsAssoc()
    {
        return self::query()->where('status', self::STATUS_ACTIVE)->pluck('name', 'id');
    }

    public function assignUserToShop($shop, $user_id)
    {
        $exists = $shop->users()->wherePivot('user_id', $user_id)->exists();

        if (!$exists) {
            $shop->users()->attach($user_id);
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'shop_user');
    }

    public function shopOwner()
    {
        return $this->belongsTo(User::class, 'shop_owner_id', 'id');
    }

    public function get_shops_assoc()
    {
        return self::query()->where('status', self::STATUS_ACTIVE)->pluck('name', 'id');
    }

    // public function membershipCardType(): BelongsTo
    // {
    //     return $this->belongsTo(MembershipCardType::class);
    // }
    // public function getshop(){
    //     return self::query()->where('status',self::STATUS_ACTIVE)->pluck('name','id');
    // }
    // public function get_membershiptype(){
    //     return self::query()->where('status',self::STATUS_ACTIVE)->pluck('card_type_name','id' );
    // }


    // *****************


    public function membershipCardTypes(): HasMany
    {
        return $this->hasMany(MembershipCardType::class, 'shop_id', 'id');
    }

    public function total_shop(){
        return self::where('status', self::STATUS_ACTIVE)->count();
    }
    public function assigned_user()
    {
        return $this->belongsToMany(User::class, 'shop_user', 'shop_id', 'user_id');
    }

    public function get_assigned_shops()
    {
        return self::query()->where('status', self::STATUS_ACTIVE)->whereHas('assigned_user', function ($q){
            $q->where('user_id', Auth::id());
        })->get();
    }
}

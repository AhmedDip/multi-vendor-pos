<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Exception;
use Illuminate\Http\Request;
use App\Manager\Utility\Utility;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Collection;
use App\Manager\ImageUploadManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public const DEFAULT_PASSWORD = '12345678';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */


    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_BLOCKED = 3;
    public const STATUS_PENDING = 4;
    public const STATUS_SUSPENDED = 5;
    public const STATUS_REJECTED = 6;

    public const STATUS_LIST = [
        self::STATUS_INACTIVE  => 'Inactive',
        self::STATUS_ACTIVE    => 'Active',
        self::STATUS_SUSPENDED => 'Suspended',
        self::STATUS_BLOCKED   => 'Blocked',
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_REJECTED  => 'Rejected',
    ];

    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/user-photos/';
    public const PHOTO_WIDTH = 600;
    public const PHOTO_HEIGHT = 600;

    public const IMAGE_TYPE_PROFILE = 1;
    public const IMAGE_TYPE_COVER = 2;
    public const IMAGE_TYPE_NID_FRONT = 3;
    public const IMAGE_TYPE_NID_BACK = 4;

    public const IMAGE_TYPE_LIST = [
        self::IMAGE_TYPE_PROFILE   => 'Profile photo',
        self::IMAGE_TYPE_COVER     => 'Cover photo',
        self::IMAGE_TYPE_NID_FRONT => 'NID front side',
        self::IMAGE_TYPE_NID_BACK  => 'NID back side'
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }


    final public function get_admins(Request $request, array|null $columns = null): LengthAwarePaginator
    {
        $query = self::query()->with('roles');

        if ($columns) {
            $query->select($columns);
        }

        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('phone')) {
            $query->where('phone', $request->input('phone'));
        }
        if ($request->input('email')) {
            $query->where('email', $request->input('email'));
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('role')) {
            $query->whereHas('roles', function ($query) use ($request) {
                $query->where('id', $request->input('role'));
            });
        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'desc');
            $query->orderBy($request->input('order_by_column', 'id'), $direction);
        }
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    final public function get_user_by_id(int $user_id)
    {
        return self::query()->find($user_id);
    }


    /**
     * @param Request $request
     * @return Collection|Model|null
     * @throws Exception
     */
    final public function update_own_profile(Request $request): Collection|Model|null
    {
        $user = self::query()->with('profile_photo')->findOrFail(Auth::id());
        if ($user) {
            $user->update([
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ]);
            if ($request->has('photo')) {
                $this->upload_profile_photo($request, $user);
            }
        }
        return $user;
    }

    /**
     * @return MorphOne
     */
    final public function profile_photo(): MorphOne
    {
        return $this->morphOne(MediaGallery::class, 'imageable')
            ->where('type', self::IMAGE_TYPE_PROFILE)
            ->orderByDesc('id');
    }

    /**
     * @throws Exception
     */
    public function upload_profile_photo(Request $request, User|Model $user): void
    {
        Cache::forget('admin_profile_photo');
        $file = $request->file('photo');
        if (is_string($request->input('photo'))) {
            $file = Storage::get($request->input('photo'));
        }
        if (!$file) {
            return;
        }
        $photo      = (new ImageUploadManager)->file($file)
            ->name(Utility::prepare_name($user->name))
            ->path(self::PHOTO_UPLOAD_PATH)
            ->auto_size()
            ->watermark(true)
            ->upload();
        $media_data = [
            'photo'   => self::PHOTO_UPLOAD_PATH . $photo,
            'type'    => self::IMAGE_TYPE_PROFILE,
            'shop_id' => $request->input('shop_id', null)
        ];
        if ($user->profile_photo && !empty($user->profile_photo->photo)) {
            ImageUploadManager::deletePhoto($user->profile_photo->photo);
            $user->profile_photo->delete();
        }
        $user->photo()->create($media_data);
    }

    /**
     * @return MorphOne
     */
    final public function photo(): MorphOne
    {
        return $this->morphOne(MediaGallery::class, 'imageable');
    }


    final public function storeShopOwner(array $data, $request = null)
    {
        $shop_owner = self::query()->create($data);
        if ($request->has('photo')) {
            $this->upload_profile_photo($request, $shop_owner);
        }
        return $shop_owner;
    }


    public function store_sales_executive($request)
    {
        $data            = $this->prepare_data($request);
        $sales_executive = self::query()->create($data['user']);

        // $sales_executive->assignRole('Sales Executive');

        if ($request->has('role_id') && count($request->input('role_id')) > 0) {
            $sales_executive->roles()->sync($request->input('role_id'));
        }

        $sales_executive->syncShops($request->input('shop_id'));

        $this->upload_profile_photo($request, $sales_executive);

        return $sales_executive;
    }


    public function update_sales_executive($request, User $user)
    {
        $data = $this->prepare_data($request, $user);
        $user->update($data['user']);
        // $user->assignRole('Sales Executive');
        if ($request->has('role_id') && count($request->input('role_id')) > 0) {
            $user->roles()->sync($request->input('role_id'));
        }
        $this->upload_profile_photo($request, $user);
        if (!$user->isAssignedToShop($request->input('shop_id'))) {
            $user->syncShops($request->input('shop_id'));
        }
        return $user;
    }

    public function store_shop_owner($request)
    {
        $data       = $this->prepare_data($request);
        $shop_owner = self::query()->create($data['user']);
        $shop_owner->assignRole('Shop Owner');
        $shop_owner->shops()->sync($request->input('shop_id'));
        $this->upload_profile_photo($request, $shop_owner);
        return $shop_owner;
    }

    public function update_shop_owner($request, User $user)
    {
        $data = $this->prepare_data($request, $user);
        $user->update($data['user']);
        $user->assignRole('Shop Owner');
        $this->upload_profile_photo($request, $user);
        $user->shops()->sync($request->input('shop_id'));
        return $user;
    }


    public function prepare_data($request, User $user = null): array
    {
        if ($user) {
            $data['user'] = [
                'name'     => $request->input('name') ?? $user->name,
                'email'    => $request->input('email') ?? $user->email,
                'phone'    => $request->input('phone') ?? $user->phone,
                'address'  => $request->input('address') ?? $user->address,
                'password' => Hash::make($request->input('password')) ?? $user->password,
                'status'   => $request->input('status') ?? $user->status,
            ];
        } else {
            $data['user'] = [
                'name'     => $request->input('name'),
                'email'    => $request->input('email'),
                'phone'    => $request->input('phone'),
                'address'  => $request->input('address'),
                'password' => Hash::make($request->input('password')) ?? Hash::make(self::DEFAULT_PASSWORD),
                'status'   => $request->input('status') ?? self::STATUS_ACTIVE,
            ];
        }
        return $data;
    }

    public function get_shop_owners()
    {
        return self::query()->whereHas('roles', static function ($query) {
            $query->where('name', 'like', '%Shop Owner%');
        })->pluck('name', 'id');
    }

    public function delete_user(User $user)
    {
        if ($user->profile_photo && !empty($user->profile_photo->photo)) {
            ImageUploadManager::deletePhoto($user->profile_photo->photo);
            $user->profile_photo->delete();
        }
        $user->roles()->detach();
        $user->shops()->detach();
        $user->delete();
    }

    public function get_sales_executives(Request $request)
    {
        $query = self::query()->with('roles', 'shops');

        if ($request->input('shop_id')) {
            $query->whereHas('shops', static function ($query) use ($request) {
                $query->where('shop_id', $request->input('shop_id'));
            });
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('phone')) {
            $query->where('phone', $request->input('phone'));
        }
        if ($request->input('email')) {
            $query->where('email', $request->input('email'));
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('order_by_column')) {
            $query->orderBy($request->input('order_by_column'), $request->input('order_by') ?? 'DESC');
        }

        return $query->whereHas('roles', static function ($query) {
            // $query->where('name', 'like', '%Sales Executive%');
            $query->where('name', 'like', '%Employee%')->orWhere('name', 'like', '%Sales Executive%');
        })->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function get_shop_owners_data(Request $request)
    {
        $query = self::query()->with('roles', 'shops');

        if ($request->input('shop_id')) {
            $query->whereHas('shops', static function ($query) use ($request) {
                $query->where('shop_id', $request->input('shop_id'));
            });
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('phone')) {
            $query->where('phone', $request->input('phone'));
        }
        if ($request->input('email')) {
            $query->where('email', $request->input('email'));
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('order_by_column')) {
            $query->orderBy($request->input('order_by_column'), $request->input('order_by') ?? 'DESC');
        }

        return $query->whereHas('roles', static function ($query) {
            $query->where('name', 'like', '%Shop Owner%');
        })->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }


    final public function activity_logs(): MorphMany
    {
        return $this->morphMany(ActivityLog::class, 'logable')->orderByDesc('id');
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shop_user');
    }

    public function destroy_sales_executive(User $user)
    {
        if ($user->profile_photo && !empty($user->profile_photo->photo)) {
            ImageUploadManager::deletePhoto($user->profile_photo->photo);
            $user->profile_photo->delete();
        }
        $user->roles()->detach();
        $user->shops()->detach();
        $user->delete();
    }


    public function syncShops($shopId)
    {
        $this->shops()->sync($shopId);
    }

    public function isAssignedToShop($shopId)
    {
        return $this->shops()->where('shop_id', $shopId)->exists();
    }

    public function get_sales_executive_dependency_data()
    {
        $roles = RoleExtended::where('role_type', \App\Models\RoleExtended::ROLE_TYPE_USER)
            ->select('id', 'name')
            ->where('name', '!=', 'Shop Owner')->get();
        return $roles;
    }

    public function get_emp_roles()
    {
        return RoleExtended::where('role_type', \App\Models\RoleExtended::ROLE_TYPE_USER)
            ->select('id', 'name')
            ->where('name', '!=', 'Shop Owner')->pluck('name', 'id');
    }

    public function get_employee_data($request)
    {
        if ($request->input('shop_id')) {
            return self::query()->whereHas('shops', static function ($query) use ($request) {
                $query->where('shop_id', $request->input('shop_id'));
            })->whereHas('roles', static function ($query) {
                $query->where('name', 'like', '%Employee%');
            })->get();
        } else {
            return self::query()->whereHas('roles', static function ($query) {
                $query->where('name', 'like', '%Employee%');
            })->get();
        }
    }

    public function assinged_shop()
    {
        return $this->belongsToMany(Shop::class, 'shop_user', 'user_id', 'shop_id');
    }
}

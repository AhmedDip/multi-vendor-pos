<?php

namespace App\Models;

use App\Manager\Constants\GlobalConstant;
use App\Manager\ImageUploadManager;
use App\Manager\Utility\Utility;
use App\Models\Traits\CreatedUpdatedBy;
use App\Observers\PackageObserver;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

#[ObservedBy(PackageObserver::class)]
class Package extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/package/';
    public const PHOTO_TYPE_COVER = 1;

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

    /**
     * @throws Exception
     */
    private function upload_photo(Request $request, Model|Package $package)
    {
        $file = $request->file('photo');
        if (is_string($request->input('photo'))) {
            $file = Storage::get($request->input('photo'));
        }
        if (!$file) {
            return;
        }
        $photo = (new ImageUploadManager)->file($file)
            ->name(Utility::prepare_name($request->input('plan')))
            ->path(self::PHOTO_UPLOAD_PATH)
            ->auto_size()
            ->upload();

        $media_data = [
            'photo' => self::PHOTO_UPLOAD_PATH . $photo,
            'type'  => self::PHOTO_TYPE_COVER
        ];
        if ($package->photo && !empty($package->photo->photo)) {
            ImageUploadManager::deletePhoto($package->photo->photo);
            $package->photo->delete();
        }
        $package->photo()->create($media_data);
    }

    private function prepare_data(Request $request){
        return[
            'plan'=>$request->input('plan'),
            'tagline'=>$request->input('tagline'),
            'quota'=>$request->input('quota'),
            'sort_order'=>$request->input('sort_order'),
            'price'=>$request->input('price'),
            'status'=>$request->input('status'),
            'shop_id'=>$request->input('shop_id'),
        ];
    }

    public function get_package(Request $request):LengthAwarePaginator
    {
        $query=self::query()->with('photo')->orderBy('id', 'desc');
        if ($request->input('shop_id')) {
            $query = self::query()->where('shop_id', $request->input('shop_id'));
        }
        if ($request->input('plan')) {
            $query->where('plan', 'like', '%' . $request->input('plan') . '%');
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('sort_order') && !empty($request->input('sort_order'))) {
            $query->orderBy($request->input('sort_order', 'id'), $request->input('order_by', 'desc') ?? 'desc');
        }
        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_package(Request $request){
        $package = self::query()->create($this->prepare_data($request));
        if ($request->has('photo')) {
            $this->upload_photo($request, $package);
        }
        return $package;    
    }

    public function update_package(Request $request, Package $package){
        $package->update($this->prepare_data($request));
        if ($request->has('photo')) {
            $this->upload_photo($request, $package);
        }
        return $package;    }

    public function delete_package(Package $package){
        if ($package->photo) {
            ImageUploadManager::deletePhoto($package->photo->photo);
            $package->photo->delete();
        }
        return $package->delete();
    }

    public function get_package_for_api(Request $request){
        return cache()->rememberForever('packages',function() use($request){
            return self::query()->where('status', self::STATUS_ACTIVE)->where('shop_id',$request->shop_id)->get();
        });
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}

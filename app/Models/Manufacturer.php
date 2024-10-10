<?php

namespace App\Models;

use App\Manager\Constants\GlobalConstant;
use App\Manager\ImageUploadManager;
use App\Manager\Utility\Utility;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Manufacturer extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/manufacturer/';
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
    private function upload_photo(Request $request, Model|Manufacturer $brand)
    {
        $file = $request->file('photo');
        if (is_string($request->input('photo'))) {
            $file = Storage::get($request->input('photo'));
        }
        if (!$file) {
            return;
        }
        $photo = (new ImageUploadManager)->file($file)
            ->name(Utility::prepare_name($request->input('name')))
            ->path(self::PHOTO_UPLOAD_PATH)
            ->auto_size()
            ->upload();

        $media_data = [
            'photo' => self::PHOTO_UPLOAD_PATH . $photo,
            'type'  => self::PHOTO_TYPE_COVER,
            'shop_id' => $request->input('shop_id', null)
        ];
        if ($brand->photo && !empty($brand->photo->photo)) {
            ImageUploadManager::deletePhoto($brand->photo->photo);
            $brand->photo->delete();
        }
        $brand->photo()->create($media_data);
    }

    private function prepare_data(Request $request)
    {
        return [
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('slug')),
            'sort_order' => $request->input('sort_order'),
            'status' => $request->input('status'),
            'shop_id' => $request->input('shop_id'),
        ];
    }

    public function get_manufacturer(Request $request): LengthAwarePaginator
    {
        $query = self::query()->with('photo')->orderBy('id', 'desc');
        if ($request->input('shop_id')) {
            $query = self::query()->where('shop_id', $request->input('shop_id'));
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->input('sort_order') && !empty($request->input('sort_order'))) {
            $query->orderBy($request->input('sort_order', 'id'), $request->input('order_by', 'desc') ?? 'desc');
        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public function store_manufacturer(Request $request)
    {
        $manufacturer = self::query()->create($this->prepare_data($request));
        if ($request->has('photo')) {
            $this->upload_photo($request, $manufacturer);
        }
        return $manufacturer;
    }

    public function update_manufacturer(Request $request, Manufacturer $manufacturer)
    {
        $manufacturer->update($this->prepare_data($request));
        if ($request->has('photo')) {
            $this->upload_photo($request, $manufacturer);
        }
        return $manufacturer;
    }

    public function delete_manufacturer(Manufacturer $manufacturer)
    {
        if ($manufacturer->photo) {
            ImageUploadManager::deletePhoto($manufacturer->photo->photo);
            $manufacturer->photo->delete();
        }
        return $manufacturer->delete();
    }

    public function get_manufacturer_for_api(Request $request){
        return cache()->rememberForever('manufacturers',function() use($request){
            return self::query()->where('status', self::STATUS_ACTIVE)->where('shop_id',$request->shop_id)->get();
        });
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    public function get_manufacturer_assoc_by_shop(){
        return self::query()->where('status', self::STATUS_ACTIVE)->get()->groupBy('shop_id');
    }

    public function get_manufacturer_assoc_for_api(Request $request){
        return self::query()->where('shop_id',$request->shop_id)->select('id','name')->get();
    }
}

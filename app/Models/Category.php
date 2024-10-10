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

class Category extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];
    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/category/';
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
    private function upload_photo(Request $request, Model|Category $category)
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
        if ($category->photo && !empty($category->photo->photo)) {
            ImageUploadManager::deletePhoto($category->photo->photo);
            $category->photo->delete();
        }
        $category->photo()->create($media_data);
    }

    private function prepare_data(Request $request)
    {
        return [
            'shop_id'     => $request->input('shop_id'),
            'name'        => $request->input('name'),
            'slug'        => Str::slug($request->input('slug')),
            'status'      => $request->input('status'),
            'sort_order'  => $request->input('sort_order'),
            'description' => $request->input('description'),
        ];
    }

    public function get_category(Request $request): LengthAwarePaginator
    {
        // $query=self::query()->with(['photo'])->where('shop_id', $request->input('shop_id'));
        if ($request->input('shop_id')) {
            $query = self::query()->with(['photo'])->where('shop_id', $request->input('shop_id'));
        } else {
            $query = self::query()->with(['photo']);
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

    public function store_category(Request $request)
    {
        // dd($request->all());
        $category = self::query()->create($this->prepare_data($request));
        if ($request->has('photo')) {
            $this->upload_photo($request, $category);
        }
        return $category;
    }

    public function update_category(Request $request, Model|Category $category)
    {
        $category->update($this->prepare_data($request));
        if ($request->has('photo')) {
            $this->upload_photo($request, $category);
        }
        return $category;
    }

    public function delete_category(Model|Category $category)
    {
        if ($category->photo) {
            ImageUploadManager::deletePhoto($category->photo->photo);
            $category->photo->delete();
        }
        return $category->delete();
    }

    // public function get_category_for_api(): mixed
    // {
    //     return cache()->rememberForever('categories',function(){
    //         return self::query()->where('status', self::STATUS_ACTIVE)->with('photo')->get();
    //     });
    // }


    public function get_category_assoc(){
        return self::query()->with('photo')->where('status', self::STATUS_ACTIVE)->pluck('name', 'id');
    }
    public function getAllcategory()
    {
        return self::query()->where('status', self::STATUS_ACTIVE)->pluck('name', 'id');
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function get_category_group_by_shop(){
        return self::query()->with('shop')->where('status', self::STATUS_ACTIVE)->get()->groupBy('shop_id');
    }

    public function get_category_assoc_for_api(Request $request){
        return self::query()->where('shop_id',$request->shop_id)->select('id','name')->get();
    }

  

    public function get_categories_data(Request $request)
    {
        return self::where('status', self::STATUS_ACTIVE)
            ->where('shop_id', $request->header('shop_id'))
            ->select('id', 'name')
            ->get();
    }
}



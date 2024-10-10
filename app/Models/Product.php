<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Manager\PriceManager;
use App\Manager\Utility\Utility;
use App\Manager\ImageUploadManager;
use Illuminate\Support\Facades\Log;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Manager\Constants\GlobalConstant;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const STATUS_ACTIVE   = 1;
    public const STATUS_INACTIVE = 2;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];

    public const TYPE_PRODUCT = 1;
    public const TYPE_SERVICE = 2;

    public const TYPE_LIST = [
        self::TYPE_PRODUCT => 'Product',
        self::TYPE_SERVICE => 'Service',
    ];



    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/product-photos/';
    public const PHOTO_WIDTH       = 600;
    public const PHOTO_HEIGHT      = 600;

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

    public function get_products($request): LengthAwarePaginator
    {
        if ($request->input('shop_id')) {
            $query = self::query()->with(['category', 'photo', 'shop'])->where('shop_id', $request->input('shop_id'));
        } else {
            $query = self::query()->with(['category', 'photo', 'shop']);
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('sku')) {
            $query->where('sku', $request->input('sku'));
        }
        if ($request->input('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->input('type')) {
            $query->where('type', $request->input('type'));
        }
        if ($request->input('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }
        if ($request->input('price')) {
            $query->where('price', $request->input('price'));
        }
        if ($request->input('discount_price')) {
            $query->where('discount_price', $request->input('discount_price'));
        }
        if ($request->input('stock')) {
            $query->where('stock', $request->input('stock'));
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





    public function store_product($request): Builder | Model
    {
        $data  = $this->prepare_data($request);
        $product = self::query()->create($data['product']);
        if (!empty($data['attribute_values']) && count($data['attribute_values']) > 0) {
            $product->attributeValues()->sync($data['attribute_values']);
        }
        $this->upload_photo($request, $product);
        return $product;
    }

    public function update_product($request, Product $product): Builder | Model
    {
        $data = $this->prepare_data($request, $product);

        $product->update($data['product']);
        $this->upload_photo($request, $product);
        if (!empty($data['attribute_values'])) {
            $product->attributeValues()->sync($data['attribute_values']);
        }

        return $product;
    }



    public function prepare_data($request, Product $product = null): array
    {
        if ($product) {
            $data['product'] = [
                'name'           => $request->input('name') ?? $product->name,
                'slug'           => $request->input('slug') ?? $product->slug,
                'sku'            => $request->input('sku') ?? $product->sku,
                'description'    => $request->input('description') ?? $product->description,
                'brand_id'       => $request->input('brand_id') ?? $product->brand_id,
                'category_id'    => $request->input('category_id') ?? $product->category_id,
                'warehouse_id'   => $request->input('warehouse_id') ?? $product->warehouse_id,
                'manufacturer_id' => $request->input('manufacturer_id') ?? $product->manufacturer_id,
                'shelf_location' => $request->input('shelf_location') ?? $product->shelf_location,
                'price'          => $request->input('price') ?? $product->price,
                'cost_price'     => $request->input('cost_price') ?? $product->cost_price,
                'discount_price' => $request->input('discount_price') ?? $product->discount_price,
                'stock'          => $request->input('stock') ?? $product->stock,
                'sort_order'     => $request->input('sort_order') ?? $product->sort_order,
                'status'         => $request->input('status') ?? $product->status,
                'shop_id'        => $request->input('shop_id') ?? $product->shop_id,
                'type'           => $request->input('type') ?? $product->type,
                'duration'       => $request->input('duration') ?? $product->duration,
                'slot'           => $request->input('slot') ?? $product->slot,
                'expiry_date'    => $request->input('expiry_date') ?? $product->expiry_date,
            ];
            $data['attribute_values'] = array_filter($request->input('attribute_values', $product->attributeValues->pluck('id')->toArray()));
        } else {
            $data['product'] = [
                'name'            => $request->input('name'),
                'slug'            => $request->input('slug'),
                'sku'             => $request->input('sku'),
                'description'     => $request->input('description'),
                'brand_id'        => $request->input('brand_id'),
                'category_id'     => $request->input('category_id'),
                'warehouse_id'    => $request->input('warehouse_id'),
                'manufacturer_id' => $request->input('manufacturer_id'),
                'shelf_location'  => $request->input('shelf_location'),
                'price'           => $request->input('price'),
                'cost_price'      => $request->input('cost_price'),
                'discount_price'  => $request->input('discount_price'),
                'stock'           => $request->input('stock'),
                'sort_order'      => $request->input('sort_order') ?? 0,
                'status'          => $request->input('status'),
                'shop_id'         => $request->input('shop_id'),
                'type'            => $request->input('type'),
                'duration'        => $request->input('duration'),
                'slot'            => $request->input('slot'),
                'expiry_date'     => $request->input('expiry_date'),
            ];
            $data['attribute_values'] = array_filter($request->input('attribute_values'));
        }
        return $data;
    }


    private function upload_photo(Request $request, Product|Model $product): void
    {
        $photos = $request->photo;
        if (is_string($request->input('photo'))) {
            $photos = explode(' , ', $request->input('photo'));
        }
        if (!$photos || !is_array($photos)) {
            return;
        }

        foreach ($photos as $photo) {
            if (is_string($photo)) {
                $file = Storage::get($photo);
            } else {
                $file = $photo;
            }
            if (!$file) {
                continue;
            }
            $photo      = (new ImageUploadManager)->file($file)
                ->name(Utility::prepare_name($product->name))
                ->path(self::PHOTO_UPLOAD_PATH)
                ->auto_size()
                ->watermark(true)
                ->upload();
            $media_data = [
                'photo' => self::PHOTO_UPLOAD_PATH . $photo,
                'type'  => null,
                'shop_id' => $request->input('shop_id', null)
            ];
            if ($product->photo && !empty($product->photo->photo)) {
                ImageUploadManager::deletePhoto($product->photo->photo);
                $product->photo->delete();
            }
            $product->photo()->create($media_data);
        }
    }




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

    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product');
    }

    public function getAttributeValuesIds()
    {
        return $this->attributeValues->pluck('id');
    }

    public function selectedAttributeNameAndValues()
    {
        return $this->attributeValues->map(function ($attributeValue) {
            $attributeName      = $attributeValue?->attribute?->name;
            $attributeValueId   = $attributeValue?->id;
            $attributeValueName = $attributeValue?->name;

            return [
                'label'             => $attributeName,
                'name'              => $attributeValueName,
                'value'            => $attributeValueId,
            ];
        });
    }





    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function destroy_product(Product $product): bool
    {
        $product->photos->each(function ($photo) {
            if ($photo->photo && !empty($photo->photo)) {
                ImageUploadManager::deletePhoto($photo->photo);
                $photo->delete();
            }
        });
        $product->attributeValues()->detach();
        return $product->delete();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function get_product_type_service_and_group_by_category()
    {
        return self::query()->with('category')
            ->where('status', self::STATUS_ACTIVE)
            ->where('type', self::TYPE_SERVICE)
            ->get()
            ->groupBy('category_id');
    }



    public function update_stock($product, $quantity)
    {
        $product = Product::query()->find($product);

        if (!$product) {
            throw new Exception('Product not found.');
        }

        if (($product->stock < $quantity) && $product->type == self::TYPE_PRODUCT) {
            throw new Exception('Product stock is not available. Please check the stock.');
        }

        if ($product->type == self::TYPE_SERVICE) {
            $today_sold = OrderItem::query()
                ->whereHas('order', function ($query) {
                    $query->whereDate('order_date', Carbon::today()->toDateString());
                })
                ->where('product_id', $product->id)
                ->sum('quantity');


            if ($today_sold + $quantity > $product->slot) {
                throw new Exception('Service slot is already full. Please check the slot.');
            }
        }


        // $product->update(
        //     [
        //         'stock' => $product->stock - $quantity,
        //         'sold' => $product->sold + $quantity
        //     ]
        // );

        if ($product->type == self::TYPE_SERVICE) {
            $product->update(
                [
                    'sold' => $product->sold + $quantity
                ]
            );
        } else {
            $product->update(
                [
                    'stock' => $product->stock - $quantity,
                    'sold' => $product->sold + $quantity
                ]
            );
        }
    }


    public static function getFormattedStatusList()
    {
        $formattedStatusList = [];
        foreach (self::STATUS_LIST as $id => $name) {
            $formattedStatusList[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        return $formattedStatusList;
    }

    public static function getFormattedTypeList()
    {
        $formattedTypeList = [];
        foreach (self::TYPE_LIST as $id => $name) {
            $formattedTypeList[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        return $formattedTypeList;
    }

    public function get_products_assoc()
    {
        return self::query()->pluck('name', 'id');
    }


    public function get_inventory($request): LengthAwarePaginator
    {
        if ($request->input('shop_id')) {
            $query = self::query()->with(['category', 'photo'])->where('shop_id', $request->input('shop_id'));
        } else {
            $query = self::query()->with(['category', 'photo']);
        }
        if ($request->input('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->input('sku')) {
            $query->where('sku', $request->input('sku'));
        }
        if ($request->input('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->input('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }
        if ($request->input('price')) {
            $query->where('price', $request->input('price'));
        }
        if ($request->input('discount_price')) {
            $query->where('discount_price', $request->input('discount_price'));
        }
        if ($request->input('stock')) {
            $query->where('stock', $request->input('stock'));
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

        // return self::query()->select('id','name','sku','stock','price','discount_price','expiry_date','sold')->with('photo')->paginate(15);
        return $query->select('id', 'name', 'sku', 'stock', 'price', 'discount_price', 'expiry_date', 'sold')->with('photo')->paginate(15);
    }


    public function total_product($shop_id = null)
    {
        if ($shop_id == null) {
            return self::where('status', self::STATUS_ACTIVE)->count();
        } else {
            return self::where('status', self::STATUS_ACTIVE)->where('shop_id', $shop_id)->count();
        }
    }


    public function top_product()
    {
        return self::select('name', 'sold')
            ->orderBy('sold', 'DESC')
            ->take(5)
            ->get();
    }


    public function total_top_product($shop_id = null)
    {
        if ($shop_id == null) {
            return self::where('status', self::STATUS_ACTIVE)->where('sold', '>', 5)->count();
        } else {
            return self::where('status', self::STATUS_ACTIVE)
                ->where('sold', '>', 5)
                ->where('shop_id', $shop_id)
                ->count();
        }
    }

    public function total_sold_product($shop_id = null)
    {
        if ($shop_id == null) {
            return self::where('status', self::STATUS_ACTIVE)->sum('sold');
        } else {
            return self::where('status', self::STATUS_ACTIVE)->where('shop_id', $shop_id)->sum('sold');
        }
    }

    public function get_services_data(Request $request)
    {
        return self::where('type', self::TYPE_SERVICE)->where('status', self::STATUS_ACTIVE)
            ->where('shop_id', $request->header('shop_id'))
            ->select('id', 'name')
            ->get();
    }

    public function get_product_data()
    {
        return self::select('id', 'name', 'price', 'shop_id')->get();
    }

}

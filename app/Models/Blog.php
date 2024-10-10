<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Manager\Utility\Utility;
use Illuminate\Support\Collection;
use App\Manager\ImageUploadManager;
use Illuminate\Support\Facades\Auth;
use App\Models\Traits\CreatedUpdatedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Manager\Constants\GlobalConstant;
use App\Observers\BlogObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

#[ObservedBy(BlogObserver::class)]
class Blog extends Model
{
    use HasFactory, CreatedUpdatedBy, SoftDeletes;

    protected $guarded = [];

    public const PHOTO_UPLOAD_PATH = 'public/photos/uploads/blog-photos/';
    public const PHOTO_WIDTH       = 600;
    public const PHOTO_HEIGHT      = 600;

    public const STATUS_ACTIVE   = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_FEATURED = 3;

    public const STATUS_LIST = [
        self::STATUS_ACTIVE   => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
        self::STATUS_FEATURED => 'Featured',
    ];

    public const IS_COMMENT_ALLOWED_YES = 1;
    public const IS_COMMENT_ALLOWED_NO  = 0;

    public const IS_COMMENT_ALLOWED_LIST = [
        self::IS_COMMENT_ALLOWED_YES => 'Yes',
        self::IS_COMMENT_ALLOWED_NO  => 'No',
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


    private function upload_photo(Request $request, Blog|Model $blog): void
    {
        $file = $request->file('photo');
        if (is_string($request->input('photo'))) {
            $file = Storage::get($request->input('photo'));
        }
        if (!$file) {
            return;
        }
        $photo      = (new ImageUploadManager)->file($file)
            ->name(Utility::prepare_name($blog->title))
            ->path(self::PHOTO_UPLOAD_PATH)
            ->auto_size()
            ->watermark(true)
            ->upload();
        $media_data = [
            'photo' => self::PHOTO_UPLOAD_PATH . $photo,
            'type'  => null,
        ];
        if ($blog->photo && !empty($blog->photo?->photo)) {
            ImageUploadManager::deletePhoto($blog->photo?->photo);
            $blog->photo->delete();
        }
        $blog->photo()->create($media_data);
    }

    public function get_blogs(Request $request, array $column = null, bool $all = null, bool $only_active = false): Collection | LengthAwarePaginator
    {
        $query = self::query()->with(['photo', 'categories']);
        if ($column) {
            $query->select($column);
        }

        if ($request->input('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->input('status')) {
            $query->where('status', $request->input('status'));
        }
    
        if ($request->input('category_id')) {
            $query->whereHas('categories', function ($query) use ($request) {
                $query->where('blog_categories.id', $request->input('category_id'));
            });
        }
        if ($only_active) {
            $query->where('status', '!=', self::STATUS_INACTIVE);
        }
        if ($request->input('order_by_column')) {
            $direction = $request->input('order_by', 'asc') ?? 'asc';
            $query->orderBy($request->input('order_by_column'), $direction);
        } else {
            $query->orderBy('id', 'desc');
        }

        if ($all) {
            return $query->get();
        }

        return $query->paginate($request->input('per_page', GlobalConstant::DEFAULT_PAGINATION));
    }

    public final function get_blog($key, $value, $column = null): Blog | Model
    {
        $query = self::query()->with(['author', 'photo', 'categories', 'comments']);
        if ($column) {
            $query->select($column);
        }
        return $query->where($key, $value)->firstOrFail();
    }

    public function prepare_data($request, Blog $blog = null): array
    {
        if ($blog) {
            $data['blog'] = [
                'title'              => $request->input('title') ?? $blog->title,
                'slug'               => $request->input('slug') ?? $blog->slug,
                'content'            => $request->input('content') ?? $blog->content,
                'summary'            => $request->input('summary') ?? $blog->summary,
                'tag'                => $request->input('tag') ?? $blog->tag,
                'is_comment_allowed' => $request->input('is_comment_allowed') ?? $blog->is_comment_allowed,
                'status'             => $request->input('status') ?? $blog->status,
            ];
            $data['categories'] = $request->input('categories') ?? [];
        } else {
            $data['blog'] = [
                'title'              => $request->input('title'),
                'slug'               => $request->input('slug'),
                'content'            => $request->input('content'),
                'summary'            => $request->input('summary') ?? null,
                'tag'                => $request->input('tag') ?? null,
                'is_comment_allowed' => $request->input('is_comment_allowed') ?? self::IS_COMMENT_ALLOWED_NO,
                'status'             => $request->input('status') ?? self::STATUS_INACTIVE,
            ];
            $data['categories'] = $request->input('categories') ?? [];
        }

        return $data;
    }

    public function store_blog($request): Builder | Model
    {
        $data = $this->prepare_data($request);
        $blog = $this->create($data['blog']);
        $blog->categories()->sync($data['categories']);
        $this->upload_photo($request, $blog);
        return $blog;
    }

    public function update_blog($request, Blog $blog): bool
    {
        $data = $this->prepare_data($request, $blog);
        $blog->update($data['blog']);
        $blog->categories()->sync($data['categories']);

        $this->upload_photo($request, $blog);
        return true;
    }

    public function delete_blog(Blog $blog): bool
    {
        $blog->categories()->detach();
        $blog->photo?->delete();
        $blog->delete();
        return true;
    }

    final public function get_featured_blogs(): Collection
    {
        return self::query()->where('status', self::STATUS_FEATURED)->orderBy('id', 'desc')->with('photo')->get();
    }

    final public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category');
    }

    /**
     * @return mixed
     */
    final public function get_blog_for_api(Request $request): mixed
    {
        return cache()->rememberForever('blogs', function () use($request){
            return self::query()
                ->with('photo')
                ->where('status', self::STATUS_ACTIVE)
                ->where('shop_id',$request->shop_id)
                ->get();
        });
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaLibraryResource;
use App\Manager\AccessControl\AccessControlTrait;
use App\Manager\ImageUploadManager;
use App\Manager\Utility\Utility;
use App\Models\MediaGallery;
use App\Http\Requests\StoreMediaGalleryRequest;
use App\Http\Requests\UpdateMediaGalleryRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Models\RoleExtended;
use App\Models\Traits\AppActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class MediaGalleryController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;

    public static string $route = 'mediaGallery';

    public function get_media_library(Request $request)
    {

        try {
            DB::beginTransaction();
            $query = MediaGallery::query()
                ->orderByDesc('id');
//            if (auth('sanctum')->check()) {
//                $query->where('user_id', auth('sanctum')->id());
//            }

            if (auth('sanctum')->check()) {
                $user       = auth()->user();
                $role_types = $user->roles->pluck('role_type')->toArray();
                $role_names = $user->roles->pluck('name')->toArray();
                $shop_ids   = DB::table('shop_user')->where('user_id', $user->id)->pluck('shop_id')->toArray();

                if (in_array(RoleExtended::ROLE_TYPE_ADMIN, $role_types, true)) {

                } elseif (in_array(RoleExtended::ROLE_TYPE_USER, $role_types, true)) {
                    $query->whereIn('shop_id', $shop_ids);
                } else {
                    throw ValidationException::withMessages(['message' => 'User does not have access to this feature']);
                }

                if ($request->input('path')) {
                    $query->where('photo', 'like', $request->input('path') . '%');
                }
                $count_query = clone $query;
                if ($request->input('take')) {
                    $query->take($request->input('take'));
                } else {
                    $query->take(24);
                }

                $media_gallery         = $query->get();
                $directories           = Storage::directories('public/photos/uploads');
                $formatted_directories = [];
                foreach ($directories as $directory) {
                    $is_active = false;
                    if ($request->input('path') && $request->input('path') == $directory) {
                        $is_active = true;
                    }
                    $formatted_directories[] = [
                        'name'      => ucwords(str_replace([' - ', '_'], ' ', basename($directory))),
                        'path'      => $directory,
                        'is_active' => $is_active
                    ];
                }

                $this->data           = [
                    'media_gallery' => MediaLibraryResource::collection($media_gallery)->response()->getData(),
                    'links'         => $formatted_directories,
                    'media_count'   => $count_query->count()
                ];
                $this->status_message = __('Profile basic info updated successfully');

            }else{
                throw ValidationException::withMessages(['message' => 'User does not have access to this feature']);
            }



            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PROFILE_DATA_FOR_SELECTION_FAILED', $throwable, 'error');
            $this->status_message = 'Failed!' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function create_new_directory(Request $request)
    {
        try {
            DB::beginTransaction();
            if ($request->input('path') == '') {
                $directory = 'public/photos/uploads/' . Str::slug($request->input('folder_name'));
            } else {
                $directory = dirname(trim($request->input('path'))) . '/' . Str::slug($request->input('folder_name'));
            }

            $path = Storage::path($directory);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }
            $this->status_message = __('Directory created successfully');
            $this->data           = [
                'name'      => ucwords(str_replace([' - ', '_'], ' ', basename($directory))),
                'path'      => $directory,
                'is_active' => true
            ];
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('DIRECTORY_CREATION_FAILED', $throwable, 'error');
            $this->status_message = 'Failed!' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function upload_media_library(Request $request)
    {
        try {
            DB::beginTransaction();
            if (empty($request->input('path'))) {
                $path = 'public/photos/uploads/temp/';
            } else {
                $path = $request->input('path') . '/';
            }


            $photo         = (new ImageUploadManager)->file($request->file('file'))
                ->name(Utility::prepare_name('media-library'))
                ->path($path)
                ->auto_size()
                ->watermark(true)
                ->upload();
            $media_data    = [
                'photo'          => $path . $photo,
                'type'           => null,
                'imageable_id'   => 1,
                'imageable_type' => MediaGallery::class,
                'shop_id'        => $request->input('shop_id', null)
            ];
            $media_gallery = MediaGallery::query()->create($media_data);

            $this->data = new MediaLibraryResource($media_gallery);

            $this->status_message = __('Image uploaded successfully');
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('MEDIA_LIBRARY_IMAGE_UPLOAD_FAILED_FAILED', $throwable, 'error');
            $this->status_message = 'Failed!' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function delete_media_library(Request $request, int|null $id = null)
    {
        try {
            DB::beginTransaction();
            $media_gallery = MediaGallery::query()->find($request->input('id', $id));
            if ($media_gallery) {
                ImageUploadManager::deletePhoto($media_gallery->photo);
                $media_gallery->delete();
            }

            $this->status_message = __('Image deleted successfully');
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('MEDIA_LIBRARY_IMAGE_DELETION_FAILED_FAILED', $throwable, 'error');
            $this->status_message = 'Failed!' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }
}

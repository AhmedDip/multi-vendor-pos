<?php

namespace App\Manager\UI;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class UIUtilityManager
{

    final public static function get_profile_photo(): string
    {
        if (Cache::has('admin_profile_photo')) {
            return Cache::get('admin_profile_photo');
        }
        $profile_photo = asset('admin_assets/images/users/user-1.jpg');
        $user          = User::query()->with('profile_photo')->findOrFail(Auth::id());
        if ($user && $user->profile_photo) {
            $profile_photo = Storage::url($user?->profile_photo?->photo);
        }
        Cache::put('admin_profile_photo', $profile_photo);
        return $profile_photo;
    }
}

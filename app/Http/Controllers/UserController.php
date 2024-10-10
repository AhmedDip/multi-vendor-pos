<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminProfileUpdateRequest;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class UserController extends Controller
{

    /**
     * @return View
     */
    final public function create(): View
    {
        $cms_content = [
            'module'       => __('Profile'),
            'module_url'   => route('profile.create'),
            'active_title' => __('Update'),
//            'button_type'  => 'create',
//            'button_title' => __('Menu Create'),
//            'button_url'   => route('menu.create'),
        ];
        $user        = Auth::user();
        //$user?->load(['profile_photo']);

        return view('admin.modules.admin-profile.create',
            compact('cms_content', 'user'));
    }

    /**
     * @param AdminProfileUpdateRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    final public function store(AdminProfileUpdateRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            (new User())->update_own_profile($request);
            DB::commit();
            success_alert('Profile updated successfully');
            return redirect()->back();
        } catch (Throwable $throwable) {
            DB::rollBack();
            app_error_log('PROFILE_UPDATE_FAILED', $throwable, 'error');
            failed_alert($throwable->getMessage());
            return redirect()->back();
        }
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * @method validate(Request $request, array[] $array)
 */
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function changePassword()
    {
        $cms_content = [
            'module'       => __('Password'), // page title and breadcrumb's first element title
            'module_url'   => route('dashboard'),
            'active_title' => __('Change'), // breadcrumb's active title
            'button_type'  => 'back', // list|create|edit, page right button
            'button_title' => __('Back'),
            'button_url'   => route('dashboard'),
        ];
        return view('admin.modules.password.change-password', compact('cms_content'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

    }
}

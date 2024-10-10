<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\WelcomeMail;
use Illuminate\Support\Str;
use App\Models\RoleExtended;
use Illuminate\Http\Request;
use App\Mail\PasswordResetMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Traits\AppActivityLog;
use App\Http\Requests\LinkEmailRequest;
use App\Http\Requests\StoreUserRequest;
use App\Manager\API\Traits\CommonResponse;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserDetailsResource;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UpdateProfileApiRequest;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\ShopOwnerDetailsResource;
use App\Manager\AccessControl\AccessControlTrait;

class ApiAuthController extends Controller
{
    use CommonResponse, AppActivityLog, AccessControlTrait;
    final public function registration(StoreUserRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $registration_data = $request->except('photo', 'confirm_password');
            $registration_data['status'] = User::STATUS_ACTIVE;
            $registration_data['password'] = Hash::make($request->input('password'));

            $shop_owner = (new User())->storeShopOwner($registration_data, $request);

            $original    = $shop_owner->getOriginal();
            $changed     = $shop_owner->getChanges();
            self::activityLog($request,$original,$changed,$shop_owner);

            $role = RoleExtended::query()->where('name', 'like', '%Shop Owner%')->first();

            if ($role) {
                $shop_owner->roles()->sync($role->id);
            }

            $token = $shop_owner->createToken('POS')->plainTextToken;

            $this->data = [
                'token' => $token,
                'user' => new UserDetailsResource($shop_owner),
            ];
            $this->status_message = __('Registration Successful As a Shop Owner.');
            DB::commit();
            $email_data = [
                'name'=>$shop_owner->name,
            ];
            Mail::to($registration_data['email'])->send(new WelcomeMail($email_data));
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::info('REGISTRATION_FAILED_AS_A_SHOP_OWNER', ['data' => $request->all(), 'error' => $throwable]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }


    final public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            if (auth()->attempt($credentials)) {
                $user = Auth::user();
                $roleName = $user->roles->first()->name;
                $token = $user->createToken('POS')->plainTextToken;

                $this->data = [
                    'token' => $token,
                    'user' => new UserDetailsResource($user),
                ];
                $this->status_message = __('Login Successful As a ' . $roleName . '.');
            } else {
                $this->status_message = __('Invalid Credentials.');
                $this->status_code = $this->status_code_failed;
                $this->status = false;
            }
        } catch (Throwable $throwable) {
            Log::error('LOGIN_FAILED', [
                'data' => $request->all(),
                'error' => $throwable
            ]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }

        return $this->commonApiResponse();
    }

    final public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            $this->status_message = __('Logged Out Successfully.');
        } catch (Throwable $throwable) {
            Log::error('LOGOUT_FAILED', [
                'data' => $request->all(),
                'error' => $throwable
            ]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }



    public function update_profile(UpdateProfileApiRequest $request, int $id): JsonResponse
    {
        try {
            $user = (new User())->get_user_by_id($id);

            if ($user) {
                $user->update([
                    'name'     => $request->input('name'),
                    'email'    => $request->input('email'),
                    'phone'    => $request->input('phone'),
                ]);

                $user->upload_profile_photo($request, $user);

                $this->data           = new ShopOwnerDetailsResource($user);
                $this->status_message = __('Profile Updated Successfully.');
                DB::commit();
            } else {
                $this->status_message = __('User Not Found.');
                $this->status_code    =  $this->status_code_failed;
                $this->status         = false;
            }
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::info('PROFILE_UPDATE_FAILED', ['data' => $request->all(), 'error' => $throwable]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

    public function get_profile_by_auth_user(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->data = new UserDetailsResource($user);
            $this->status_message = __('Profile Details.');
        } catch (Throwable $throwable) {
            Log::info('PROFILE_GET_FAILED', ['data' => $request->all(), 'error' => $throwable]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
        return $this->commonApiResponse();
    }

    final public function send_reset_link_email(LinkEmailRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $email = $request->input('email');
    
            $user = User::query()
                ->where('email', $email)
                ->where('status', User::STATUS_ACTIVE)
                ->first();
    
            if (!$user) {
                throw ValidationException::withMessages(['email' => 'User not found']);
            }
    
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();
    
            $token = Str::random(60);  
            $hashedToken = Hash::make($token);
    
            DB::table('password_reset_tokens')->insert(
                [
                    'email'      => $email,
                    'token'      => $hashedToken,
                    'created_at' => Carbon::now(),
                ]
            );
    
            $data = [
                'token' => $token,
                'email' => $email,
                'name'  => $user->name,
                'url'   => env('FRONT_END_APP_URL') . '/reset-password?token=' . $token . '&email=' . urlencode($email),
            ];
    
            Mail::to($email)->send(new PasswordResetMail($data));
    
            $this->data = [
                'otp'   => false,
                'token' => $token,
                'email' => $email,
                'name'  => $user->name,
                'url'   => $data['url'],
            ];
            $this->status_message = __('Password reset link sent to your email');
    
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::info('PASSWORD RESET FAILED', ['data' => $request->all(), 'error' => $throwable]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code = $this->status_code_failed;
            $this->status = false;
        }
    
        return $this->commonApiResponse();
    }

    /**
     * @throws ValidationException
     */
    // final public function reset_password(Request $request): JsonResponse
    // {
    //     $request->validate( [
    //         // 'token'    => 'required',
    //         // 'email'    => 'required|email',
    //         'password' => 'required|min:8',
    //     ]);
    //     try {
    //         DB::beginTransaction();
    //         //match token and email with database
    //         $passwordReset = DB::table('password_reset_tokens')
    //             ->where('email', $request->email)
    //             ->first();

    //         if (!$passwordReset) {
    //             throw ValidationException::withMessages(['email' => 'Token not found']);
    //         }

    //         //check if token is expired
    //         if (Carbon::parse($passwordReset->created_at)->addMinutes(30)->isPast()) {
    //             throw ValidationException::withMessages(['email' => 'Token expired']);
    //         }
    //         //check if token matches
    //         if (!Hash::check($request->token, $passwordReset->token)) {
    //             throw ValidationException::withMessages(['email' => 'Token mismatch']);
    //         }
    //         //update password
    //         $user = User::query()
    //             ->where('email', $request->email)
    //             ->where('status', 1)
    //             ->first();
    //         if (!$user) {
    //             throw ValidationException::withMessages(['email' => 'Customer not found']);
    //         }

    //         User::query()
    //             ->where('email', $request->email)
    //             ->update(['password' => Hash::make($request->password)]);

    //         //delete token
    //         DB::table('password_reset_tokens')
    //             ->where('email', $request->email)
    //             ->delete();
    //         $this->status_message = __('Password updated successfully');
    //         DB::commit();
    //     } catch (Throwable $throwable) {
    //         Log::info('PASSWORD RESET FAILED', ['data' => $request->all(), 'error' => $throwable]);
    //         $this->status_message = 'Failed! ' . $throwable->getMessage();
    //         // $this->status_code    = CommonResponse::STATUS_CODE_FAILED;
    //         $this->status_code=$this->status_code_failed;
    //         $this->status         = false;
    //     }
    //     return $this->commonApiResponse();
    // }



   public function reset(ResetPasswordRequest $request)
{
    try {
        DB::beginTransaction();

        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset) {
            throw ValidationException::withMessages(['email' => 'Token not found']);
        }

        if (Carbon::parse($passwordReset->created_at)->addMinutes(30)->isPast()) {
            throw ValidationException::withMessages(['email' => 'Token expired']);
        }

        if (!Hash::check($request->input('token'), $passwordReset->token)) {
            throw ValidationException::withMessages(['email' => 'Token mismatch']);
        }

        $user = User::query()
            ->where('email', $request->email)
            ->first();
        
        if (!$user) {
            throw ValidationException::withMessages(['email' => 'Customer not found']);
        }

        User::query()
            ->where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        DB::commit();

        $this->status_code    = $this->status_code_success;
        $this->status         = true;
        $this->status_message = __('Password updated successfully');
        return $this->commonApiResponse();
    } catch (Throwable $throwable) {
        DB::rollBack();
        Log::info('PASSWORD_RESET_FAILED', ['data' => $request->all(), 'error' => $throwable]);
        $this->status_message = 'Failed! ' . $throwable->getMessage();
        $this->status_code = $this->status_code_failed;
        $this->status      = false;
        return $this->commonApiResponse();
    }
}


    final public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = auth()->user();
            if ($user && Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                $this->status_message = __('Password changed successfully');
            } else {
                $this->status_message = __('Invalid old password');
            }
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::info('API_CHANGE_PASSWORD_FAILED', ['data' => $request->all(), 'error' => $throwable]);
            $this->status_message = 'Failed! ' . $throwable->getMessage();
            $this->status_code    = $this->status_code_failed;
            $this->status         = false;
        }
        return $this->commonApiResponse();
    }

  
}

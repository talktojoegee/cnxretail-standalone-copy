<?php

namespace App\Http\Controllers\API\V2\Auth;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\AdminUserResource;
use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthController extends BaseController
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors()->first(), 422, $validator->errors());
        }

        $attempt = array_merge($credentials, ['account_status' => 1]);

        // account_status may not exist on all installs; fall back to email/password only.
        $token = Auth::guard('admin_api')->attempt($attempt);
        if (! $token) {
            $token = Auth::guard('admin_api')->attempt($credentials);
        }

        if (! $token) {
            return $this->fail('Login credentials are invalid.', 401);
        }

        /** @var AdminUser $admin */
        $admin = Auth::guard('admin_api')->user();
        if (isset($admin->account_status) && (int) $admin->account_status !== 1) {
            Auth::guard('admin_api')->logout();

            return $this->fail('Account is disabled.', 403);
        }

        return $this->ok([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('admin_api')->factory()->getTTL() * 60,
            'user' => new AdminUserResource($admin),
        ], 'Login successful');
    }

    public function me(): JsonResponse
    {
        return $this->ok(new AdminUserResource(Auth::guard('admin_api')->user()));
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::guard('admin_api')->logout();
        } catch (\Throwable $e) {
            // ignore
        }

        return $this->ok(null, 'Logged out');
    }
}

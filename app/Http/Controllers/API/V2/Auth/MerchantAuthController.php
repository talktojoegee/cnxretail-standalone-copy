<?php

namespace App\Http\Controllers\API\V2\Auth;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\UserResource;
use App\Models\AdminNotification;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class MerchantAuthController extends BaseController
{
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:160',
            'first_name' => 'required|string|max:80',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'terms' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->fail($validator->errors()->first(), 422, $validator->errors());
        }

        $tenantModel = new Tenant();
        $userModel = new User();
        $tenant = $tenantModel->setNewTenant($request);
        $userModel->setNewUser($request, $tenant);

        (new AdminNotification())->setNewAdminNotification(
            'New registration',
            "There's a new registration to CNX Retail. Kindly check it out.",
            'view-tenant',
            $tenant->slug,
            1,
            0
        );

        return $this->created([
            'tenant' => [
                'id' => $tenant->id,
                'company_name' => $tenant->company_name,
                'slug' => $tenant->slug,
            ],
        ], 'Account created');
    }

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

        JWTAuth::factory()->setTTL(10080);
        $token = Auth::guard('api')->attempt($credentials);

        if (! $token) {
            return $this->fail('Login credentials are invalid.', 401);
        }

        /** @var User $user */
        $user = Auth::guard('api')->user();
        $users = $user->getAllTenantUsersByTenantId($user->tenant_id);
        $endDate = optional($user->getTenant)->end_date;

        if ($endDate && strtotime(now()) > strtotime($endDate)) {
            foreach ($users as $row) {
                $row->account_status = 0;
                $row->save();
            }
            Auth::guard('api')->logout();

            return $this->fail('Renew Subscription', 403);
        }

        foreach ($users as $row) {
            $row->account_status = 1;
            $row->save();
        }

        $user->load('getTenant.getTenantPlan');

        return $this->ok([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => new UserResource($user),
        ], 'Login successful');
    }

    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::guard('api')->user();
        $user->load('getTenant.getTenantPlan');

        return $this->ok(new UserResource($user));
    }

    public function logout(): JsonResponse
    {
        try {
            Auth::guard('api')->logout();
        } catch (\Throwable $e) {
            // Token may already be invalid.
        }

        return $this->ok(null, 'Logged out');
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = Auth::guard('api')->refresh();
        } catch (\Throwable $e) {
            return $this->fail('Could not refresh token.', 401);
        }

        return $this->ok([
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
        ], 'Token refreshed');
    }
}

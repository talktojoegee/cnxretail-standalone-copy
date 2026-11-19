<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\AdminUserResource;
use App\Models\AdminActivityLog;
use App\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminUserController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->ok(AdminUserResource::collection((new AdminUser())->getAllAdminUsers()));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => 'required',
            'email' => 'required|email|unique:admin_users,email',
            'mobile_no' => 'required',
            'password' => 'required',
        ]);

        (new AdminUser())->createNewAdminUser($request);
        AdminActivityLog::record('Admin user', 'Created admin account for '.$request->email);

        $user = AdminUser::query()->where('email', $request->email)->first();

        return $this->created(new AdminUserResource($user), 'Admin user created');
    }
}

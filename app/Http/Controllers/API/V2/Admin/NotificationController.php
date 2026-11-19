<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;

class NotificationController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->ok((new AdminNotification())->getNotifications());
    }
}

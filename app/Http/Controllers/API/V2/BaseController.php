<?php

namespace App\Http\Controllers\API\V2;

use App\Http\Controllers\API\V2\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;

abstract class BaseController extends Controller
{
    use RespondsWithJson;
}

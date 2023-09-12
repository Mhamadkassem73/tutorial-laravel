<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class testController extends ApiController
{
    public function testFunction()
    {
        return ApiController::successResponse("test",200);
    }
}

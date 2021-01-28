<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function detail(Request $request)
    {
        return $this->responseJson(UserResource::make($request->user()));
    }
}

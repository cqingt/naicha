<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends CommonController
{
    public function index()
    {
        $data = $userInfo = [];

        return response()->json($data, 200);
    }

    public function orders()
    {
        // 订单，优惠券，口味
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [])
    {
        $result = ['code' => 1, 'msg' => '操作成功', 'data' => $data];

        return response()->json($result);
    }

    public function error($msg = '')
    {
        $result = ['code' => 0, 'msg' => $msg ? : '操作失败'];

        return response()->json($result);
    }
}

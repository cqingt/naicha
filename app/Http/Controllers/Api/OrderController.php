<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/9/21
 * Time: 11:03
 */
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use function EasyWeChat\Kernel\Support\generate_sign;

class OrderController extends CommonController
{
    // 创建订单
    public function create(Request $request)
    {
        $data = $request->all();
        $temperature = $data['temperature'];
        $sugar = $data['sugar'];
        $weight = $data['weight'];
        $double = (int)$data['double'];
        $goodsId = $data['list'];

        dump($data);
        exit;
    }
}
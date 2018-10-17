<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/10/17
 * Time: 9:45
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function index()
    {
        require_once base_path() . '\\app\\Library\\weixinPay\\lib\\PayNotifyCallBack.php';
        require_once base_path() . '\\app\\Library\\weixinPay\\lib\\WxPay.Config.php';

        $notify = new \PayNotifyCallBack();
        $config = new \WxPayConfig();

        $notify->Handle($config, false);
    }
}
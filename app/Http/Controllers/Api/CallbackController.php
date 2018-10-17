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
use App\Library\weixinPay\lib\PayNotifyCallBack;
use App\Library\weixinPay\lib\WxPayConfig;

class CallbackController extends Controller
{
    public function index()
    {

        $notify = new PayNotifyCallBack();

        $config = new WxPayConfig();

        $notify->Handle($config, false);
    }
}
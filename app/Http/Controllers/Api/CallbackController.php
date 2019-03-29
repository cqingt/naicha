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
use DB;

class CallbackController extends Controller
{
    public function index()
    {
        $notify = new PayNotifyCallBack();

        $config = new WxPayConfig();

        DB::table('crontab_log')->insert(
            [
                'status' => 'debug',
                'content' => var_export(file_get_contents("php://input"), true),
                'created_at' => date('Y-m-d H:i:s')
            ]
        );

        $notify->Handle($config, false);
    }
}
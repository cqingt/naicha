<?php
/**
 * 交易相关
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/9/14
 * Time: 15:17
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Library\weixinPay\lib\PayNotifyCallBack;
use App\Library\weixinPay\lib\WxPayUnifiedOrder;
use App\Library\weixinPay\lib\WxPayApi;
use App\Library\weixinPay\lib\WxPayConfig;
use DB;

class BusinessController extends Controller
{
    // 平台订单支付状态查询
    public function orderQuery(Request $request)
    {
        $orderId = $request->get('orderId');

        if (empty($orderId)) {
            return $this->_error('参数不能为空');
        }

        $orderInfo = Order::where(['id' => $orderId])->first()->toArray();

        if (empty($orderInfo)) {
            return $this->_error('订单不存在');
        }

        if ($orderInfo['status'] == 1) {
            return $this->_successful();
        }

        $callback = new PayNotifyCallBack();

        $result = $callback->queryOrder('', $orderInfo['order_sn']);

        if ($result) {
            return $this->_successful();
        } else {
            return $this->_error('订单未支付');
        }
    }

    /**
     * 支付回调地址
     * @return string
     */
    public function callbackUrl(Request $request)
    {
        return $request->root() . '/business/callback';
    }

    //微信支付
    public function weixinPay(Request $request)
    {
        $unifiedOrder = new WxPayUnifiedOrder();
        $orderId = $request->get('orderId'); // 订单号
        $openId = $request->get('openid'); // 订单号

        if (empty($orderId) || empty($openId)) {
            return $this->_error('参数不能为空');
        }

        $orderInfo = Order::where(['id' => $orderId])->get()->toArray();

        if (empty($orderInfo)) {
            return $this->_error('订单不存在');
        }

        $orderInfo = $orderInfo[0];

        if  ($orderInfo['status'] != 0) {
            return $this->_error('订单已支付');
        }

        $orderDetail = OrderDetail::where('order_id', $orderId)->first();
        $weixinConfig = new WxPayConfig();

        $unifiedOrder->SetAppid($weixinConfig->GetAppId());
        $unifiedOrder->SetBody($orderDetail['goods_name']);
        $unifiedOrder->SetMch_id($weixinConfig->GetMerchantId());
        $unifiedOrder->SetNonce_str(WxPayApi::getNonceStr());
        $unifiedOrder->SetNotify_url($this->callbackUrl($request));
        $unifiedOrder->SetOpenid($openId);
        $unifiedOrder->SetSpbill_create_ip($request->ip());
        $unifiedOrder->SetOut_trade_no($orderInfo['order_sn']);
        $unifiedOrder->SetTotal_fee(bcmul(0.01, 100, 0)); // $orderInfo['price']*100
        $unifiedOrder->SetTrade_type('JSAPI');

        $result = WxPayApi::unifiedOrder($weixinConfig, $unifiedOrder);

        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $time = time();

            $data['timeStamp'] = (string)$time;//时间戳
            $data['nonceStr'] = $result['nonce_str'];//随机字符串
            $data['signType'] = 'MD5';                        //签名算法，暂支持 MD5
            $data['package'] = 'prepay_id=' . $result['prepay_id'];

            // 小程序前端调起支付的paySign 需要再加密
            $signParam = [
                'appId' => $result['appid'],
                'nonceStr' => $result['nonce_str'],
                'package' => $data['package'],
                'signType' => 'MD5',
                'timeStamp' => $data['timeStamp'],
                'key' => $weixinConfig->GetKey()
            ];

            $md5Str = '';
            foreach ($signParam as $key => $item) {
                if (! empty($md5Str)) {
                    $md5Str .= '&';
                }
                $md5Str .= "{$key}=" . $item;
            }

            $data['paySign'] = strtoupper(md5($md5Str));

            //$data['appId'] = $weixinConfig->GetAppId();
            //$data['out_trade_no'] = $unifiedOrder->GetOut_trade_no();

            return $this->_successful($data);
        } else {
            return $this->_error(isset($result['err_code_des']) ? $result['err_code_des'] : $result['return_msg']);
        }
    }

    public function callback()
    {
        $notify = new PayNotifyCallBack();

        $config = new WxPayConfig();

        $notify->Handle($config, false);
    }

    protected function _successful($data = [], $code = 200)
    {
        return [
            'code' => $code,
            'msg' => 'success',
            'data' => $data
        ];
    }

    protected function _error($errorMsg = '', $code = 401)
    {
        return [
            'code' => $code,
            'msg' => $errorMsg,
        ];
    }

}
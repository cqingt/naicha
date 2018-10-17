<?php
/**
 * 交易相关
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/9/14
 * Time: 15:17
 */

namespace App\Http\Controllers\Api;

use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use Illuminate\Http\Request;

class BusinessController extends CommonController
{
    // 平台订单支付状态查询
    public function orderQuery(Request $request)
    {
        $orderId = $request->get('orderId');

        if (empty($orderId)) {
            return $this->_error('PARAM_NOT_EMPTY');
        }

        $orderInfo = Order::where(['id' => $orderId, 'member_id' => $this->getUserId()])->first()->toArray();

        if (empty($orderInfo)) {
            return $this->_error('UNKNOWN_ERROR', '订单不存在');
        }

        require_once base_path() . '\\app\\Library\\weixinPay\\lib\\PayNotifyCallBack.php';

        $callback = new \PayNotifyCallBack();

        $result = $callback->queryOrder('', $orderInfo['order_sn']);

        return $this->_successful($result);
    }

    /**
     * 支付回调地址
     * @return string
     */
    public function callbackUrl(Request $request)
    {
        return $request->root() . '/callback/index';
    }

    //微信支付
    public function weixinPay(Request $request)
    {
        require_once base_path() . '\\app\\Library\\weixinPay\\lib\\Wxpay.Api.php';
        require_once base_path() . '\\app\\Library\\weixinPay\\lib\\Wxpay.Data.php';
        require_once base_path() . '\\app\\Library\\weixinPay\\lib\\Wxpay.Config.php';

        $unifiedOrder = new \WxPayUnifiedOrder();
        $orderId = $request->get('orderId'); // 订单号

        if (empty($orderId)) {
            return $this->_error('PARAM_NOT_EMPTY');
        }

        $orderInfo = Order::where(['id' => $orderId, 'status' => 0, 'member_id' => $this->getUserId()])->first()->toArray();

        if (empty($orderInfo)) {
            return $this->_error('UNKNOWN_ERROR', '订单不存在');
        }

        $orderDetail = OrderDetail::where('order_id', $orderId)->first();

        $wxConfig = config('web.weixin');
        $unifiedOrder->SetAppid($wxConfig['app_id']);
        $unifiedOrder->SetBody($orderDetail['goods_name']);
        $unifiedOrder->SetMch_id($wxConfig['mch_id']);
        $unifiedOrder->SetNonce_str(\WxPayApi::getNonceStr());
        $unifiedOrder->SetNotify_url($this->callbackUrl($request));
        $unifiedOrder->SetOpenid($this->_openid);
        $unifiedOrder->SetSpbill_create_ip($request->ip());
        $unifiedOrder->SetOut_trade_no($orderInfo['order_sn']);
        $unifiedOrder->SetTotal_fee(bcmul($orderInfo['price'], 100, 2));
        $unifiedOrder->SetTrade_type('JSAPI');

        $weixinConfig = new \WxPayConfig();
        $sign = $unifiedOrder->SetSign($weixinConfig);

        $result = \WxPayApi::unifiedOrder($weixinConfig, $unifiedOrder);

        if ($result['return_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
            $time = time();

            $data['timeStamp'] = "$time";//时间戳
            $data['nonceStr'] = $unifiedOrder->GetNonce_str();//随机字符串
            $data['signType'] = 'MD5';                        //签名算法，暂支持 MD5
            $data['package'] = 'prepay_id=' . $result['PREPAY_ID'];
            $data['paySign'] = $sign;
            $data['out_trade_no'] = $unifiedOrder->GetOut_trade_no();

            return $this->_successful($data);
        } else {
            return $this->_error('UNKNOWN_ERROR', $result['return_msg']);
        }
    }

}
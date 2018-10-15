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
use function EasyWeChat\Kernel\Support\generate_sign;

class BusinessController extends CommonController
{
    public function pay()
    {
        $wechat = app('wechat');
        dump($wechat->server);
        exit;
        $wechat->server->demo();


        $payment = \EasyWeChat::payment(); // 微信支付

        // $app->order->queryByOutTradeNumber("商户系统内部的订单号（out_trade_no）");

        $result = $payment->order->unify([
            'body' => '用户下单',
            'out_trade_no' => '233456232',
            'trade_type' => 'JSAPI',  // 必须为JSAPI
            'openid' => '334232432432432', // 这里的openid为付款人的openid
            'total_fee' => 1, // 总价 * 100
        ]);

        // 如果成功生成统一下单的订单，那么进行二次签名
        if ($result['return_code'] === 'SUCCESS') {
            // 二次签名的参数必须与下面相同
            $params = [
                'appId' => config('web.weixin')['app_id'],
                'timeStamp' => time(),
                'nonceStr' => $result['nonce_str'],
                'package' => 'prepay_id=' . $result['prepay_id'],
                'signType' => 'MD5',
            ];

            // config('wechat.payment.default.key')为商户的key
            $params['paySign'] = generate_sign($params, config('wechat.payment.default.key'));

            return $params;
        } else {
            return $result;
        }
    }

    public function orderStatus()
    {
        $orderInfo = [];
        // 未支付，查询支付平台
        if ($orderInfo['status'] == 0) {
            Loader::import('wxpay/lib/PayNotifyCallBack', EXTEND_PATH);
            $callback = new \PayNotifyCallBack();
            // 查询订单状态，更新订单状态，用户账户余额，账户变化日志
            $result = $callback->queryOrder('', $orderInfo['order_sn']);
            if ($result) {
                return ['status' => Constant::PAY_STATUS_SUCCESS];
            }
        }
        return ['status' => Constant::PAY_STATUS_UNPAID];
    }

    // 平台订单支付状态查询
    protected function orderQuery($payType, $orderSn)
    {
        Loader::import('wxpay/lib/PayNotifyCallBack', EXTEND_PATH);
        $callback = new \PayNotifyCallBack();
        // 查询订单状态，更新订单状态，用户账户余额，账户变化日志
        $result = $callback->queryOrder('', $orderSn);
        return $result;
    }

    // 微信支付
    public function wxpay($payTitle, $orderSn, $amount)
    {
        Loader::import('wxpay/lib/WxPay#Api', EXTEND_PATH);
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($payTitle);
        //$input->SetAttach($payTitle); //  附加信息
        $input->SetOut_trade_no($orderSn);
        $input->SetTotal_fee($amount * 100); //微信支付，单位(分)
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($payTitle);
        $input->SetNotify_url(self::callbackUrl());
        $input->SetTrade_type("APP");
        $order = \WxPayApi::unifiedOrder($input);
        $params = $this->makeWxPayParams($order);
        $backInfo = [
            'orderSn' => $orderSn,
            'totalAmount' => (string)($amount * 100),
        ];
        $backInfo = array_merge($backInfo, $params);

        return $backInfo;
    }

    /**
     * 预支付
     * @param $UnifiedOrderResult
     * @return mixed
     */
    protected function makeWxPayParams($UnifiedOrderResult)
    {
        if (!array_key_exists("appid", $UnifiedOrderResult)
            || !array_key_exists("prepay_id", $UnifiedOrderResult)
            || empty($UnifiedOrderResult['prepay_id'])) {
            throw new \WxPayException("参数错误");
        }
        $api = new \WxPayAppPay();
        $api->SetValue('appid', $UnifiedOrderResult["appid"]);
        $api->SetValue('package', "Sign=WXPay");
        $api->SetValue('prepayid', $UnifiedOrderResult['prepay_id']);
        $api->SetValue('partnerid', \WxPayConfig::MCHID);
        $api->SetValue('noncestr', \WxPayApi::getNonceStr());
        $api->SetValue('timestamp', (string)time());
        $api->SetValue('sign', $api->MakeSign());
        return $api->GetValues();
    }

    // 微信回调
    public function callback()
    {
        Loader::import('wxpay/lib/PayNotifyCallBack', EXTEND_PATH);
        $notify = new \PayNotifyCallBack();
        $notify->Handle(false);
    }

    /**
     * 支付回调地址
     * @return string
     */
    public static function callbackUrl()
    {
        return request()->domain() . '/business/callback';
    }

    //微信支付
    public function weixinPay()
    {
        require_once base_path() . '/app/Library/weixinPay/lib/Wxpay.Api.php';
        require_once base_path() . '/app/Library/weixinPay/lib/Wxpay.Data.php';
        require_once base_path() . '/app/Library/weixinPay/lib/Wxpay.Config.php';

        $unifiedOrder = new \WxPayUnifiedOrder();
        $request = Request::instance();
        $orderId = $request->get('orderId'); //订单号

        if (empty($orderId)) {
            return $this->_error('PARAM_NOT_EMPTY');
        }

        $orderInfo = Order::where(['id' => $orderId, 'status' => 0, 'member_id' => $this->getUserId()])->find();

        if (empty($orderInfo)) {
            return $this->_error('UNKNOWN_ERROR', '订单不存在');
        }

        $orderDetail = OrderDetail::where('order_id', $orderId)->first();

        $wxConfig = config('web.weixin');

        $unifiedOrder->SetAppid($wxConfig['app_id']);
        $unifiedOrder->SetBody($orderDetail['goods_name']);
        $unifiedOrder->SetMch_id($wxConfig['mch_id']);
        $unifiedOrder->SetNonce_str(\WxPayApi::getNonceStr());
        $unifiedOrder->SetNotify_url(self::callbackUrl());
        $unifiedOrder->SetOpenid($this->_openid);
        $unifiedOrder->SetSpbill_create_ip($request->ip());
        $unifiedOrder->SetOut_trade_no($orderInfo['order_sn']);
        $unifiedOrder->SetTotal_fee(bcmul($orderInfo['price'], 100, 2));
        $unifiedOrder->SetTrade_type('JSAPI');

        $sign = $unifiedOrder->SetSign($unifiedOrder);

        $weixinConfig = new \WxPayConfig();

        $result = \WxPayApi::unifiedOrder($weixinConfig, $unifiedOrder);


        if ($result['RETURN_CODE'] == 'SUCCESS' && $result['RESULT_CODE'] == 'SUCCESS') {
            $time = time();

            $data['timeStamp'] = "$time";//时间戳
            $data['nonceStr'] = $unifiedOrder->GetNonce_str();//随机字符串
            $data['signType'] = 'MD5';//签名算法，暂支持 MD5
            $data['package'] = 'prepay_id=' . $result['PREPAY_ID'];//统一下单接口返回的 prepay_id 参数值，提交格式如：prepay_id=*
            $data['paySign'] = $sign;//签名,具体签名方案参见微信公众号支付帮助文档;
            $data['out_trade_no'] = $unifiedOrder->GetOut_trade_no();

            return $this->_successful($data);
        } else {
            return $this->_error('UNKNOWN_ERROR', $result['RETURN_MSG']);
        }
    }

}
<?php

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';
require_once 'WxPay.Config.php';
require_once '../log.php';

class PayNotifyCallBack extends WxPayNotify
{

    /**
     * 订单查询
     * @param $transactionId
     * @param string $orderSn
     * @return bool
     * @throws WxPayException
     */
    public function queryOrder($transactionId, $orderSn = '')
    {
        $input = new WxPayOrderQuery();

        if ($transactionId) {
            $input->SetTransaction_id($transactionId);
        } else if ($orderSn) {
            $input->SetOut_trade_no($orderSn);
        }

        $result = WxPayApi::orderQuery((new WxPayConfig()), $input);

        if(is_array($result) && array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && array_key_exists('trade_state', $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
            )
        {
            $orderSn   = $result['out_trade_no']; // 订单号
            $orderInfo = DB::table('orders')
                ->where('order_sn', $orderSn)
                ->select('status', 'price')
                ->first()
                ->toArray();

            if (empty($orderInfo)) {
                return false;
            }

            if ($result["trade_state"] == "SUCCESS") {
                // 支付成功
                if ($orderInfo['status'] == 1) {
                    return true;
                }

                if (array_key_exists('total_fee', $result) && $result['total_fee'] == $orderInfo['price'] * 100) {
                    return $this->updateStatus($orderSn, 1);
                }
            } elseif ($result["trade_state"] == "CLOSED") {
                // 已关闭
                if ($orderInfo['status'] == 7) {
                    return true;
                }

                return $this->updateStatus($orderSn, 7);

            } elseif ($result["trade_state"] == "PAYERROR") {
                // 支付失败
                if ($orderInfo['status'] == 5) {
                    return true;
                }
                return $this->updateStatus($orderSn, 5);
            }
        }

        return false;
    }

    /**
     * 更改订单状态
     * @param $orderSn
     * @param $status
     * @return mixed
     */
    protected function updateStatus($orderSn, $status) {
        return  DB::table('orders')->where('order_sn', $orderSn)->update(['status' => $status]);
    }

    /**
     *  重写回调函数
     * @param WxPayNotifyResults $objData
     * @param WxPayConfigInterface $config
     * @param string $msg
     * @return bool|true回调出来完成不需要继续回调，false回调处理未完成需要继续回调
     * @throws WxPayException
     */
    public function NotifyProcess($objData, $config, &$msg)
    {
        $data = $objData->GetValues();

        //进行参数校验
        if(!array_key_exists("return_code", $data)
            ||(array_key_exists("return_code", $data) && $data['return_code'] != "SUCCESS")) {
            //TODO失败,不是支付成功的通知
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            $msg = "异常异常";
            return false;
        }

        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }

        // 进行签名验证
        try {
            $checkResult = $objData->CheckSign($config);
            if($checkResult == false){
                //签名错误
                Log::ERROR("签名错误...");
                return false;
            }
        } catch(Exception $e) {
            Log::ERROR(json_encode($e));
        }

        // 处理业务逻辑
        Log::DEBUG("call back:" . json_encode($data));

        //查询订单，判断订单真实性
        if(! $this->queryOrder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }

        return true;
    }
}
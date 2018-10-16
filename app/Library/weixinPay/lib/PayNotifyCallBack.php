<?php

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';

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

        $result = WxPayApi::orderQuery($input);

        if(array_key_exists("return_code", $result)
            && array_key_exists("result_code", $result)
            && array_key_exists('trade_state', $result)
            && $result["return_code"] == "SUCCESS"
            && $result["result_code"] == "SUCCESS"
            )
        {
            $orderSn   = $result['out_trade_no']; // 订单号
            $orderInfo = [];

            if (empty($orderInfo)) {
                return false;
            }

            if ($result["trade_state"] == "SUCCESS") {
                // 支付成功
                if ($orderInfo['pay_status'] == 1) {
                    return true;
                }

                if (array_key_exists('total_fee', $result) && $result['total_fee'] == $orderInfo['pay_amount'] * 100) {
                    // 校验金额， 更新订单状态，用户账户余额，账户变化日志
                    //return $ttbPay->paySuccess($orderSn, $result['transaction_id']);
                }
            } elseif ($result["trade_state"] == "CLOSED") {
                // 已关闭
                if ($orderInfo['pay_status'] == 3) {
                    return true;
                }

//                return $ttbPay->updateOrder(
//                    ['pay_status'  => 3, 'update_time' => NOW_TIMESTAMP],
//                    ['order_sn' => $orderSn]
//                );

            } elseif ($result["trade_state"] == "PAYERROR") {
                // 支付失败
                if ($orderInfo['pay_status'] == 2) {
                    return true;
                }

//                return $ttbPay->updateOrder(
//                    ['pay_status'  => 2, 'update_time' => NOW_TIMESTAMP],
//                    ['order_sn' => $orderSn]
//                );
            }
        }

        return false;
    }

    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        if (! array_key_exists("transaction_id", $data)) {
            return false;
        }

        //查询订单，判断订单真实性
        if (! $this->queryOrder($data["transaction_id"])) {
            return false;
        }

        return true;
    }
}
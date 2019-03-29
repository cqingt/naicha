<?php
namespace App\Library\weixinPay\lib;

require_once "WxPay.Api.php";
require_once 'WxPay.Notify.php';
require_once 'WxPay.Config.php';
use Log;
use DB;

class PayNotifyCallBack extends WxPayNotify
{
    const PAY_SUCCESS = 1; // 支付成功状态
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
            $orderInfo = $this->getOrderInfo($orderSn);

            if (empty($orderInfo)) {
                return false;
            }

            if ($result["trade_state"] == "SUCCESS") {
                // 支付成功
                if ($orderInfo['status'] != 0) {
                    return true;
                }

                if (array_key_exists('total_fee', $result) && $result['total_fee'] == $orderInfo['price'] * 100) {
                    return $this->updateStatus($orderSn, self::PAY_SUCCESS, $orderInfo['member_id']);
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
     * @param $memberId
     * @return mixed
     */
    protected function updateStatus($orderSn, $status, $memberId = 0) {
        $updateArr = ['status' => $status];

        if ($status == self::PAY_SUCCESS) {
            $updateArr['payed_at'] = date('Y-m-d H:i:s');

            // 用户是否设置首推
            $formulaId = DB::table('members')->where('id', $memberId)->pluck('formula_id');

            if (empty($formulaId)) {
                $this->setIndex($orderSn);
            }
        }

        return  DB::table('orders')->where('order_sn', $orderSn)->update($updateArr);
    }

    /**
     * 设为首推
     * @param $orderSn
     */
    protected function setIndex($orderSn) {
        $orderDetails = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->select(['orders.id', 'orders.member_id', 'orders.shop_id', 'order_details.package_num',  'order_details.goods_name', 'order_details.deploy'])
            ->where('orders.order_sn', $orderSn)
            ->get();

        $currentTime = date('Y-m-d H:i:s');
        $orderId = 0;
        $memberId = 0;
        $data = [];

        foreach ($orderDetails as $detail) {
            $detail = (array)$detail;
            $name = $detail['goods_name'];
            $orderId = $detail['id'];
            $memberId = $detail['member_id'];

            if ($detail['deploy']) {
                $name = $detail['goods_name'] . '(' . $detail['deploy'] . ')';
            }

            $data[$detail['package_num']][] = $name;
        }

        foreach ($data as $num => $goodsName) {
            $item = [
                'member_id' => $memberId,
                'order_id' => $orderId,
                'shop_id' => 1,
                'package_num' => $num,
                'title' => implode('+', $goodsName),
                'updated_at' => $currentTime,
                'created_at' => $currentTime
            ];

            DB::table('formulas')->insert($item);
            $formulaId = DB::getPdo()->lastInsertId();

            // 首单 设置首推
            if ($num == 1) {
                DB::table('members')->where('id', $memberId)->update(['formula_id' => $formulaId]);
            }
        }
    }

    protected function getOrderInfo ($orderSn) {
        //查询订单，判断订单真实性
        $orderInfo = DB::table('orders')
            ->where('order_sn', $orderSn)
            ->select(['status', 'price', 'member_id'])
            ->first();

        if (empty($orderInfo)) {
            return [];
        } else {
            return (array)$orderInfo;
        }
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
            //如果有需要可以做失败时候的一些清理处理，并且做一些监控
            return false;
        }

        if(!array_key_exists("transaction_id", $data)){
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
        } catch(\Exception $e) {
            Log::ERROR(json_encode($e));
        }

        // 处理业务逻辑
        Log::DEBUG("call back:" . json_encode($data));

        $orderSn = $data['out_trade_no'];
        $orderInfo = $this->getOrderInfo($orderSn);

        if (empty($orderInfo)) {
            return false;
        }

        if ($data["result_code"] == "SUCCESS") {
            // 支付成功
            if ($orderInfo['status'] != 0) {
                return true;
            }

            if (array_key_exists('total_fee', $data) && $data['total_fee'] == $orderInfo['price'] * 100) {
                return $this->updateStatus($orderSn, self::PAY_SUCCESS, $orderInfo['member_id']);
            }
        }

        return false;
    }
}
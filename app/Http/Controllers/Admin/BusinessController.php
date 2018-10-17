<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/10/17
 * Time: 14:28
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Library\weixinPay\lib\WxPayRefund;
use App\Library\weixinPay\lib\WxPayConfig;
use App\Library\weixinPay\lib\WxPayApi;
use App\Library\weixinPay\lib\WxPayRefundQuery;

class BusinessController extends CommonController
{
    // 退款
    public function refund()
    {
        if ((isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""
                && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["out_trade_no"], $matches))
            || (isset($_REQUEST["total_fee"]) && $_REQUEST["total_fee"] != ""
                && !preg_match("/^[0-9]{0,10}$/i", $_REQUEST["total_fee"], $matches))
            || (isset($_REQUEST["refund_fee"]) && $_REQUEST["refund_fee"] != ""
                && !preg_match("/^[0-9]{0,10}$/i", $_REQUEST["refund_fee"], $matches))) {
            header('HTTP/1.1 404 Not Found');
            dump($matches);
            exit();
        }

        if (isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != "") {
            try {
                $out_trade_no = $_REQUEST["out_trade_no"];
                $total_fee = $_REQUEST["total_fee"];
                $refund_fee = $_REQUEST["refund_fee"];
                $input = new WxPayRefund();
                $input->SetOut_trade_no($out_trade_no);
                $input->SetTotal_fee($total_fee);
                $input->SetRefund_fee($refund_fee);

                $config = new WxPayConfig();
                $input->SetOut_refund_no("sdkphp" . date("YmdHis"));
                $input->SetOp_user_id($config->GetMerchantId());
                WxPayApi::refund($config, $input);
            } catch (\Exception $e) {
                dump(json_encode($e));
            }
            exit();
        }
    }

    /**
     * 退款查询
     */
    public function refundQuery()
    {
        if ((isset($_REQUEST["transaction_id"]) && $_REQUEST["transaction_id"] != ""
                && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["transaction_id"], $matches))
            || (isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != ""
                && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["out_trade_no"], $matches))
            || (isset($_REQUEST["out_refund_no"]) && $_REQUEST["out_refund_no"] != ""
                && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["out_refund_no"], $matches))
            || (isset($_REQUEST["refund_id"]) && $_REQUEST["refund_id"] != ""
                && !preg_match("/^[0-9a-zA-Z]{10,64}$/i", $_REQUEST["refund_id"], $matches))) {
            header('HTTP/1.1 404 Not Found');
            dump($matches);
            exit();
        }

        if (isset($_REQUEST["out_refund_no"]) && $_REQUEST["out_refund_no"] != "") {
            try {
                $out_refund_no = $_REQUEST["out_refund_no"];
                $input = new WxPayRefundQuery();
                $input->SetOut_refund_no($out_refund_no);
                $config = new WxPayConfig();
                WxPayApi::refundQuery($config, $input);
            } catch (\Exception $e) {
                dump(json_encode($e));
            }
            exit();
        }
    }
}
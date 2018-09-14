<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/8/24
 * Time: 9:08
 */

return [
    'pay_type' => [
        0 => '店员下单',
        1 => '微信支付'
    ],
    'order_status' => [
        0 => '待支付',
        1 => '已支付',
        2 => '待确认',
        3 => '已完成',
        4 => '已退单',
        5 => '异常'
    ],
    'temperature' => [
        'hot' => '热饮',
        'ice' => '正常冰',
        'less_ice' => '少冰',
        'none_ice' => '去冰',
    ],
    'weixin' => [
        'app_id' => 'wx0844817c7a6d15cc',
        'app_secret' => 'fe4b64e1f96f38463033927e038e97a1'
    ],
    'api_mix' => '$%*^&201@!#$', // api混淆参数
];
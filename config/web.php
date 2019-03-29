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
        5 => '异常',
        6 => '已取消',
        7 => '已关闭'
    ],
    'temperature' => [
        'hot' => '热饮',
        'ice' => '正常冰',
        'less_ice' => '少冰',
        'none_ice' => '去冰',
    ],
    'weixin' => [
        'app_id' => 'wx0844817c7a6d15cc',
        'app_secret' => 'fe4b64e1f96f38463033927e038e97a1',
        'mch_id' => '1525167011',
    ],
    'api_mix' => '$%*^&201@!#$', // api混淆参数

    // 角色对应域名
    'domain' => [
        'a1' => 'Administrator',
        'w1' => 'Clerk',
        'w2' => 'Manager'
    ],

    // submail 短信发送
    'sms_app_id' => '32768',
    'sms_app_key' => 'b9ad8a540c1723d7ed48a6353610e89d',
    'sms_send_url' => 'https://api.mysubmail.com/message/xsend.json',
    'sms_verify_code' => '58iuv2', // 验证码模板ID
    'sms_expired_time' => 15, // 15分钟有效

    // 银豹收银接口
    'yb_app_id' => '8C246093469D92427AF811EBB6D81364',
    'yb_app_key' => '516277760616528182',
    'yb_app_url' => 'https://area17-win.pospal.cn:443/',

    // 允许登录的手机号
    'allow_login' => [
        '15359982679',
        '15260983827',
        '18649777725',
        '13799952103'
    ],

    // 组合优惠券
    'coupon_group' => [
        ['id' => 5, 'name'=> '免单1杯', 'num' => 2],
        ['id' => 6, 'name'=> '买1送1', 'num' => 2],
        ['id' => 7, 'name'=> '买2送1', 'num' => 2],
    ]
];
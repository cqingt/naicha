<?php
/**
 * 接口代码说明
 * User: cqingt
 * Date: 2018/9/7
 * Time: 14:14
 */
namespace App\Library;

class Code{
    static protected $code = [
        'SUCCESS'             => ['code' => '200', 'msg' => '请求成功'],
        'UNKNOWN_CODE'        => ['code' => '400', 'msg' => 'code不存在'],
        'UNKNOWN_ERROR'       => ['code' => '401', 'msg' => '未知错误'],
        'PARAM_NOT_EMPTY'     => ['code' => '402', 'msg' => '参数不能为空'],
        'SIGN_NOT_MATCH'      => ['code' => '403', 'msg' => '签名错误'],
        'API_NOT_FOUND'       => ['code' => '404', 'msg' => '接口未找到'],
        'SERVICE_IS_BUSINESS' => ['code' => '405', 'msg' => '服务忙，请稍后'],
        'OVERFLOW_STOCK'      => ['code' => '406', 'msg' => '库存不足'],
        'SAVE_USER_ERROR'     => ['code' => '407', 'msg' => '保存用户信息失败'],
        'USER_NOT_LOGIN'      => ['code' => '408', 'msg' => '您还未登录'],
        'USER_REGISTER_ERROR' => ['code' => '409', 'msg' => '用户注册失败'],
        'UNKNOWN_FORMULA_ID'  => ['code' => '410', 'msg' => '口味ID不存在'],
        'FORMULA_HAS_LIKE'   => ['code' => '411', 'msg' => '已点赞过该配方'],

    ];
    /**
     * @param $key
     * @param string $errorMsg
     * @return array|mixed
     */
    public static function get($key, $errorMsg = '')
    {
        if (! empty($errorMsg)) {
            return array_merge(self::$code['UNKNOWN_ERROR'], ['msg' => $errorMsg]);
        } else {
            return ! empty(self::$code[$key]) ? self::$code[$key] : [];
        }
    }
}
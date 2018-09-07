<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;


class IndexController extends CommonController
{
    public function index()
    {
        $data = ['code' => '200', 'msg' => 'success', 'data' => []];

        return response()->json($data, 200);
    }

    public function pushes()
    {

    }

    public function onLogin()
    {
        $appId  = config('weixin.appId');
        $secret = config('weixin.appSecret');
        $code   = input('code');

        if (empty($code)) {
            return $this->_error('UNKNOWN_CODE');
        }

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=APP_ID&secret=APP_SECRET&js_code=APP_CODE&grant_type=authorization_code';
        $url = str_replace(['APP_ID', 'APP_SECRET', 'APP_CODE'], [$appId, $secret, $code], $url);

        $result = $this->http_get($url);

        if (! empty($result) && isset( $result['session_key'])) {
            $sessionKey = $result['session_key'];
            $openId = $result['openid'];
            $user = new User();
            if ((! $user->existOpenId($openId) && $user->addUser($openId)) || $user->existOpenId($openId)) {
                // 存储openid, 生成新的3rd_session ，接口调用凭证使用3rd_session 过期重新登录
                $session = $this->generateSession($sessionKey, $openId);
                $value = $this->generateSession($sessionKey, $openId, true);

                Session::set($session, $value);

                $register = $user->existUsername($openId);
                $data = ['session' => $session, 'is_login' => $register ? 1 : 0, 'session_id' => session_id()];
                return $this->_successful($data);
            }
            return $this->_error('USER_REGISTER_ERROR');
        }
    }

    // 生成session
    public function generateSession($sessionKey, $openId, $getValue = false)
    {
        $value = $sessionKey . '|' . $openId;
        if ($getValue) {
            return $value;
        }
        return md5($value);
    }
}
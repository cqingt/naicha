<?php
namespace App\Http\Controllers\Api;

use App\Http\Models\Formula;
use App\Http\Models\MemberLike;
use App\Http\Models\Push;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class IndexController extends CommonController
{
    // 点赞排行
    public function index(Request $request)
    {
        $formulas = Formula::orderBy('likes', 'desc')->limit(30)->get();
        $mine = Formula::where(['member_id' => $this->getUserId()])->orderBy('likes', 'desc')->first();
        $position = 51; //默认排名 51
        $max = 1000;

        foreach ($formulas as $key => &$formula) {
            $formula['username'] = $formula->member->username;
            $formula['avatar'] = $formula->member->avatar;
            if ($formula['id'] == $mine['id']) {
                $position = $key + 1;
            }

            if ($key == 0) {
                $max = $formula['likes'];
            }
        }

        $pushes = Push::all();
//        foreach ($pushes as $key => &$push) {
//            $push['image'] = $request->root() . $push['image'];
//        }

        // 我的排名
        if (! empty($mine)) {
            $mine['position'] = $position;
            $mine['max'] = $max;
            $mine['percent'] = $max ? bcdiv($mine['likes'], $max, 2) * 100 : 100;
            $mine['username'] = $mine->member->username;
            $mine['avatar'] = $mine->member->avatar;
        }

        return $this->_successful(['formulas' => $formulas, 'pushes' => $pushes, 'mine' => $mine]);
    }

    // 点赞
    public function like(Request $request, $id)
    {
        if (MemberLike::where(['member_id' => $this->getUserId(), 'formula_id' => $id])->exists()) {
            return $this->_error('FORMULA_HAS_LIKE');
        } else {
            MemberLike::insert([
                'member_id' => $this->getUserId(),
                'formula_id' => $id,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            Formula::where('id', $id)->increment('likes', 1);
            $likes = Formula::where('id', $id)->pluck('likes');

            return $this->_successful(['likes' => $likes ]);
        }
    }

    // 从缓存中获取token
    protected function getToken()
    {
        if (Cache::has('token')) {
            $tokens = Cache::get('token');
            $tokens = $tokens ? json_decode($tokens, true, JSON_UNESCAPED_UNICODE) : '';

            if (empty($tokens)) {
                return $this->requestToken();
            } else {
                return $tokens['access_token'];
            }
        } else {
            return $this->requestToken();
        }
    }

    /**
     * 请求 token
     * @return mixed
     * @throws \Exception
     */
    protected function requestToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=_APPID_&secret=_APPSECRET_";

        $requestUrl = str_replace(['_APPID_', '_APPSECRET_'], [config('web.weixin.app_id'), config('web.weixin.app_secret')], $url);

        $result = $this->http_get($requestUrl);

        if (! empty($result) && is_array($result)) {

                Cache::put('token', json_encode($result), $result['expires_in'] / 60);

                return $result['access_token'];
        } else {
            throw new \Exception('获取token失败');
        }
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
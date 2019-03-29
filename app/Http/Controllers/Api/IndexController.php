<?php
namespace App\Http\Controllers\Api;

use App\Http\Models\Member;
use App\Http\Models\Formula;
use App\Http\Models\MemberLike;
use App\Http\Models\Push;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Cache;
use Cache;
use DB;

class IndexController extends CommonController
{
    // 点赞排行
    public function index(Request $request)
    {
        //$formulas = Formula::orderBy('likes', 'desc')->limit(30)->get();
        //$mine = Formula::where(['member_id' => $this->getUserId()])->orderBy('likes', 'desc')->first();
        $position = 0; //默认排名 51
        $max = 1;
        $userId = $this->getUserId();

        $formulas = DB::table('members')
            ->join('formulas', 'members.formula_id', '=', 'formulas.id')
            ->select(['formulas.id','formulas.likes', 'formulas.title', 'members.username', 'members.avatar', 'members.id as userId'])
            ->orderBy('formulas.likes', 'desc')
            ->get();
        $mine = [];
        foreach ($formulas as $key => $formula) {

            if ($userId == $formula->userId) {
                $mine = (array)$formula;
                $position = $key + 1;
            }

            if ($key == 0) {
                $max = $formula->likes;
            }
        }

        $pushes = [];// Push::all();
//        foreach ($pushes as $key => &$push) {
//            $push['image'] = $request->root() . $push['image'];
//        }

        // 我的排名
        if (! empty($mine)) {
            $mine['position'] = $position;
            $mine['max'] = $max;
            $mine['percent'] = $max ? bcdiv($mine['likes'], $max, 2) * 100 : 100;
        }

        return $this->_successful(['formulas' => $formulas ? : [], 'pushes' => $pushes ? : [], 'mine' => $mine ? : []]);
    }

    // 点赞,一个用户一天限制6次
    public function like(Request $request, $id)
    {
        $date = date('Y-m-d');
        $memberId = $this->getUserId();

        $times = DB::table('member_likes')->where(['member_id' => $memberId, 'date_at' => $date])->sum('times');

        if ($times >= 6) {
            return $this->_error('FORMULA_HAS_LIKE', '一天最多点赞6次');
        }

        if (MemberLike::where(['member_id' => $memberId, 'formula_id' => $id, 'date_at' => $date])->exists()) {
//            if (MemberLike::where(['member_id' => $memberId, 'formula_id' => $id, 'date_at' => $date])->pluck('times') >= 6) {
//                return $this->_error('FORMULA_HAS_LIKE', '该配方点赞已达到限制，明天再来吧');
//            } else {
                MemberLike::where(['member_id' => $this->getUserId(), 'formula_id' => $id])->increment('times', 1);

                Formula::where('id', $id)->increment('likes', 1);
                $likes = Formula::where('id', $id)->pluck('likes');

                return $this->_successful(['likes' => $likes ]);
//            }

        } else {
            MemberLike::insert([
                'member_id' => $memberId,
                'formula_id' => $id,
                'times' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'date_at' => $date
            ]);

            Formula::where('id', $id)->increment('likes', 1);
            $likes = Formula::where('id', $id)->pluck('likes');

            return $this->_successful(['likes' => $likes ]);
        }
    }

    // 获取二维码
    public function getQrcode()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=';
        $url .= $this->getToken();

        $params = ['path' => 'pages/index/index'];

        $result = $this->http_post($url, $params);
        print_r($result);exit;
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

    public function getSession(Request $request)
    {
        $openid =  $request->input('openid');
        $sessionkey = $request->input('sessionkey');

        if (empty($openid) || empty($sessionkey)) {
            return $this->_error('UNKNOWN_CODE', 'openid或sessionkey不能为空');
        }

        $value = md5($openid . config('web.api_mix') . $sessionkey);

        //Session()->set(md5($openid), $value);
        Cache::put(md5($openid), $value, 4000); // 小于3天，因为tx的session_key有效期是3天

        Cache::put($value, $openid, 4000); // 存储，用于查询 接口请求后的用户信息

        return $this->_successful(['sessionKey' => $value]);
    }

    public function getOpenId(Request $request)
    {
        $code = $request->get('code');

        if (empty($code)) {
            return $this->_error('UNKNOWN_CODE', 'code 不能为空');
        }

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=wx0844817c7a6d15cc&secret=fe4b64e1f96f38463033927e038e97a1&js_code=' . $code . '&grant_type=authorization_code';
        $weixin = file_get_contents($url);
        $jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码

        $array = get_object_vars($jsondecode);//转换成数组
        //return $array['openid'];//输出openid
        return $this->_successful(['openid' => $array['openid']]);
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

    /**
     * 获取分享的信息
     */
    public function getShareInfo(Request $request)
    {
        $shareId =  $request->input('shareId');

        if (empty($shareId)) {
            return $this->_error('UNKNOWN_CODE', '参数不能为空');
        }

        $shareInfo = DB::table('formulas')
            ->join('members', 'formulas.member_id', '=', 'members.id')
            ->select(['members.avatar', 'formulas.id', 'members.username', 'formulas.likes', 'formulas.title'])
            ->where('formulas.id', $shareId)
            ->get();

        if (! empty($shareInfo)) {
            $shareInfo = (array)$shareInfo[0];
            $shareInfo['title'] = explode('+', $shareInfo['title']);
        }

        $userNum = $userInfo = DB::table('member_likes')
            ->join('members', 'member_likes.member_id', '=', 'members.id')
            ->select(['members.avatar', 'members.id', 'members.username'])
            ->where('member_likes.formula_id', $shareId)
            ->count();

        $userInfo = DB::table('member_likes')
            ->join('members', 'member_likes.member_id', '=', 'members.id')
            ->select(['members.avatar', 'members.id', 'members.username'])
            ->where('member_likes.formula_id', $shareId)
            ->orderBy('member_likes.id')
            ->limit(8)
            ->get();

        return $this->_successful(['shareInfo' => $shareInfo, 'userInfo' => $userInfo, 'userNum' => $userNum]);
    }
}
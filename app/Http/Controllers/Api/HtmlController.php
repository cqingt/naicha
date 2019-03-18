<?php
namespace App\Http\Controllers\Api;
/**
 * 静态页面
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2019/3/13
 * Time: 11:39
 */
use App\Http\Controllers\Controller;
use App\Http\Models\SendSms;
use App\Http\Models\Coupon;
use App\Http\Models\Member;
use App\Http\Models\MemberCoupon;
use App\Library\Code;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\CrontabController;
use DB;

class HtmlController extends Controller{
    protected $request;
    protected $isLogin = false;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->isLogin = session()->get('isLogin');
    }

    // 首页
    public function index()
    {
        if (! $this->isLogin) {
            return redirect('/html/login');
        } else {
            $date = date('Y-m-d H:i:s');

            $coupons = DB::table('coupons')
                ->where('start_time', '<=', $date)
                ->where('stop_time', '>=', $date)
                ->select(['title', 'id'])
                ->orderBy('reduced_price', 'asc')
                ->get();

            // 默认选中
            $couponId = ! empty($coupons) ? $coupons[0]->id : 0;

            $couponGroup = config('web.coupon_group');
            $couponName = '';
            foreach ($couponGroup as $item) {
                if (! empty($couponName)) {
                    $couponName .= ' + ';
                }
                $couponName .= $item['name'] . '*' . $item['num'];
            }

            return view('api.html.index', ['coupons' => $coupons, 'couponId' => $couponId, 'couponGroup' => $couponName]);
        }
    }

    // 登录
    public function login()
    {
        if (! $this->isLogin) {
            return view('api.html.login');
        } else {
            return redirect('/html/index');
        }
    }

    // 发送短信接口
    public function sendSms(Request $request)
    {
        $phone = $request->get('phone');
        $allowPhone = config('web.allow_login');

        if (empty($phone)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入手机号');
        }

        if (! preg_match('/^1[345789]\d{9}$/', $phone)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入正确的手机号码');
        }

        if (! in_array($phone, $allowPhone)) {
            return $this->_error('PARAM_NOT_EMPTY', '未知手机号，请联系管理员');
        }

        $code = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= rand(0, 9);
        }

        $result = SendSms::insert([
            'phone'      => $phone,
            'code'       => $code,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if (empty($result)) {
            return $this->_error('UNKNOWN_ERROR', '数据库操作失败');
        }

        // 变量替换
        $vars = json_encode([
            'code' => $code,
            'time' => '15分钟'
        ]);

        $data = [
            'appid'     => config('web.sms_app_id'),
            'to'        => $phone,
            'project'   => config('web.sms_verify_code'),
            'vars'      => $vars,
            'signature' => config('web.sms_app_key')
        ];

        $result = $this->http_post(config('web.sms_send_url'), $data, false);

        if (!empty($result) && $result['status'] == 'success') {
            return $this->_success();
        } else {
            return $this->_error();
        }
    }

    // ajax登录
    public function ajaxLogin()
    {
        $phone = $this->request->get('phone');
        $code = $this->request->get('code');
        $allowPhone = config('web.allow_login');

        if (empty($phone) || empty($code)) {
            return $this->_error('PARAM_NOT_EMPTY', '手机号或验证码不能为空');
        }

        if (! in_array($phone, $allowPhone)) {
            return $this->_error('PARAM_NOT_EMPTY', '不被允许的手机号');
        }

        $smsInfo = SendSms::where(['phone' => $phone, 'code' => $code, 'used' => 0])
            ->orderBy('id', 'desc')
            ->first();

        $minute = config('web.sms_expired_time');

        // 15 分钟有效
        if (! empty($smsInfo)) {
            $time = strtotime($smsInfo['created_at']);

            if (time() > ($time + $minute * 60) || time() < $time) {
                return $this->_error('UNKNOWN_ERROR', '验证码已失效');
            }

            SendSms::where(['id' => $smsInfo['id']])->update(['used' => 1]);

            session(['isLogin' => true, 'phone' => $phone]);

            return $this->_success();
        } else {
            return $this->_error('PARAM_NOT_EMPTY', '验证码错误');
        }
    }

    // 发送优惠券
    public function sendCoupon()
    {
        if (! $this->isLogin) {
            return $this->_error('USER_NOT_LOGIN', '请先登录系统');
        }

        $phone = $this->request->get('phone');
        $couponId = $this->request->get('couponId');

        if (empty($phone)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入手机号');
        }

        if (! preg_match('/^1[345789]\d{9}$/', $phone)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入正确的手机号码');
        }

        if ($couponId == 1) {
            $couponInfo = config('web.coupon_group'); // 优惠券组
        } else {
            $couponInfo = Coupon::where(['id' => $couponId])->get();
        }

        if (empty($couponInfo)) {
            return $this->_error('PARAM_NOT_EMPTY', '优惠券不存在');
        }

        $memberInfo = Member::where('telephone', $phone)->get()->toArray();

        if (empty($memberInfo)) {
            return $this->_error('PARAM_NOT_EMPTY', '手机号不存在，请先绑定');
        }

        if ($couponId == 1) {
            foreach ($couponInfo as $item) {
                MemberCoupon::insert(
                    [
                        [
                            'member_id' => $memberInfo[0]['id'],
                            'coupon_id' => $item['id'],
                            'code' => $this->getCode(),
                            'created_at' => date('Y-m-d H:i:s')
                        ],
                        [
                            'member_id' => $memberInfo[0]['id'],
                            'coupon_id' => $item['id'],
                            'code' => $this->getCode(),
                            'created_at' => date('Y-m-d H:i:s')
                        ]
                    ]
                );
            }
        } else {
            MemberCoupon::insert(
                [
                    'member_id' => $memberInfo[0]['id'],
                    'coupon_id' => $couponId,
                    'code' => $this->getCode(),
                    'created_at' => date('Y-m-d H:i:s')
                ]
            );
        }

        (new CrontabController(new Request()))->updateCustomerUid($phone);

        return $this->_success();
    }

    /**
     * 生成优惠券码
     * @return string
     */
    protected function getCode()
    {
        // 生成code insert ，同步银豹用户数据
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= rand(0, 9);
            }
        }while(MemberCoupon::where('code', $code)->get()->toArray());

        return $code;
    }

    // 核销优惠券
    public function verifyCoupon()
    {
        $code = $this->request->get('code');

        if (! $this->isLogin) {
            return $this->_error('USER_NOT_LOGIN', '请先登录系统');
        }

        if (empty($code)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入优惠券码');
        }

        $couponInfo = DB::table('member_coupons')
            ->join('coupons', 'member_coupons.coupon_id', '=', 'coupons.id')
            ->select(['coupons.reduced_price', 'coupons.title', 'coupons.id', 'member_coupons.used','coupons.start_time','coupons.stop_time'])
            ->where('member_coupons.code', $code)
            ->get();

        if (empty($couponInfo)) {
            return $this->_error('PARAM_NOT_EMPTY', '优惠券码不存在');
        }

        $coupon = $couponInfo[0];
        $date = date('Y-m-d H:i:s');

        if (! ($coupon->start_time <= $date && $coupon->stop_time >= $date)) {
            return $this->_error('PARAM_NOT_EMPTY', '优惠券码已失效');
        }

        if ($coupon->used) {
            return $this->_error('PARAM_NOT_EMPTY', '优惠券码已使用');
        }

        if (MemberCoupon::where('code', $code)->update(['used' => 1])) {
            return $this->_success(['price' => $coupon->reduced_price ? : 0, 'title' => $coupon->title]);
        } else {
            return $this->_error('UNKNOWN_ERROR', '优惠券核销失败');
        }
    }

    protected function http_post($url, $data = [], $json = true, $response = 'json'){
        if(function_exists('curl_init')) {
            $urlArr = parse_url($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            if($json){
                $data = json_encode($data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
            }

            if (strnatcasecmp($urlArr['scheme'], 'https') == 0) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
            }

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);

            if(curl_errno($ch)){
                return curl_error($ch);
            }

            $info = curl_getinfo($ch);
            curl_close($ch);

            if (is_array($info) && $info['http_code'] == 200) {
                return $response == 'json' ? json_decode($output, true, JSON_UNESCAPED_UNICODE) : $output;
            } else {
                exit('请求失败（code）：' . $info['http_code']);
            }
        } else {
            throw new Exception('请开启CURL扩展');
        }
    }

    protected function _success($data = [], $code = 200)
    {
        return [
            'code' => $code,
            'msg' => 'success',
            'data' => $data
        ];
    }

    protected function _error($errorCode = 'UNKNOWN_ERROR', $errorMsg = '')
    {
        return Code::get($errorCode, $errorMsg);
    }
}
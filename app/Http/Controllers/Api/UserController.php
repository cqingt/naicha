<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2019/1/4
 * Time: 18:07
 */
namespace App\Http\Controllers\Api;

use App\Http\Models\Formula;
use App\Http\Models\Member;
use App\Http\Models\MemberCoupon;
use App\Http\Models\MemberMessage;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\SendSms;
use DB;
use Storage;
use App\Http\Controllers\Api\CrontabController;

class UserController extends CommonController
{
    public function index()
    {
        $userInfo = Member::find($this->getUserId());
        $title = '';
        $likes = 0;

        if ($userInfo && $userInfo->formula) {
            $title = $userInfo->formula->title;
            $likes = $userInfo->formula->likes;
        }

//        $userInfo = Member::where(['id' => $this->getUserId()])
//            ->select('telephone,formula_id')
//            ->get();

        return $this->_successful(['title' => $title, 'formula_id' => $userInfo['formula_id'], 'likes' => $likes, 'telephone' => $userInfo['telephone']]);
    }

    public function orders(Request $request)
    {
        $userId = $this->getUserId();
        $total = Order::where('member_id', $userId)
            ->where('status', '>', 0)
            ->count();

        $orders = Order::where('member_id', $userId)
            ->whereIn('status', [3])
            ->orderBy('id', 'desc')
            ->select(['id', 'order_sn', 'price', 'created_at', 'status'])
            ->offset($this->_offset)
            ->limit($this->_rows)
            ->get();

        $status = config('web.order_status');

        foreach ($orders as $key => &$order) {
            $details = $order->details;
            $detail = $details[0];

            $order['status'] = $status[$order['status']];
            $order['image'] = $detail['goods_image'];
            $order['title'] = $detail['goods_name'];
            $order['num'] = count($details);

            // 是否加入了口味库
            if (count($order->formulas)) {
                $order['has_taste'] = 1;
            } else {
                $order['has_taste'] = 0;
            }

            unset($orders[$key]['details']);
            unset($orders[$key]['formulas']);
        }
        $isMore = count($orders) == $this->_rows ? 1 : 0;

        $totalPage = ceil($total / $this->_rows);

        return $this->_success($orders, $isMore, $total, $this->_page, $totalPage);
    }

    // 加入口味
    public function joinTaste(Request $request, $id)
    {
        if ($id < 1) {
            return $this->_error('PARAM_NOT_EMPTY');
        }

        $order = Order::find($id);
        $details = $order->details;
        $currentTime = date('Y-m-d H:i:s');

        $data = [];
        foreach ($details as $detail) {
            $name = $detail['goods_name'];

            if ($detail['deploy']) {
                $name = $detail['goods_name'] . '(' . $detail['deploy'] . ')';
            }

            $data[$detail['package_num']][] = $name;
        }

        foreach ($data as $num => $datum) {
            $item = [
                'member_id' => $order['member_id'],
                'order_id' => $order['id'],
                'shop_id' => $order['shop_id'],
                'package_num' => $num,
                'title' => implode('+', $datum),
                'updated_at' => $currentTime,
                'created_at' => $currentTime
            ];

            if (!Formula::where(['order_id' => $order['id'], 'package_num' => $num])->exists()) {
                Formula::insert($item);
            }
        }

        return $this->_successful();
    }

    // 口味
    public function tastes($return = false)
    {
        $userId = $this->getUserId();

        $total = Formula::where('member_id', $userId)->count();

        $tastes = Formula::where('member_id', $userId)
            ->orderBy('id', 'desc')
            ->offset($this->_offset)
            ->limit($this->_rows)
            ->get();

        $isMore = count($tastes) == $this->_rows ? 1 : 0;

        $totalPage = ceil($total / $this->_rows);

        return $this->_success($tastes, $isMore, $total, $this->_page, $totalPage);
    }

    // 优惠券
    public function coupons()
    {
        $userId = $this->getUserId();
        $date = date('Y-m-d H:i:s');
        $memberCoupons = DB::table('member_coupons')
            ->join('coupons', 'member_coupons.coupon_id', '=', 'coupons.id')
            ->select(['member_coupons.id', 'coupons.title', 'coupons.stop_time', 'member_coupons.code', 'coupons.reduced_price'])
            ->where(['member_coupons.member_id' => $userId, 'member_coupons.used' => 0])
            ->where('coupons.stop_time', '>=', $date)
            ->orderBy('member_coupons.id', 'desc')
            ->get();

//        $data = [];
//
        foreach ($memberCoupons as $key => $memberCoupon) {
//            $coupon = $memberCoupon->coupon;
//            $data[$key]['id'] = $memberCoupon['id'];
//            $data[$key]['coupon_id'] = $coupon['id'];
//            $data[$key]['title'] = $coupon['title'];
            $memberCoupons[$key]->deadline = date('Y-m-d', strtotime($memberCoupon->stop_time));
//            $memberCoupons[$key]->title = $memberCoupon['id']
        }

        return $this->_successful($memberCoupons ?: []);
    }

    // 删除口味
    public function deleteTaste(Request $request, $id)
    {
        if ($id < 1) {
            return $this->_error('PARAM_NOT_EMPTY');
        }
        $userId = $this->getUserId();

        if (Formula::where(['member_id' => $userId, 'id' => $id])->exists()) {
            Formula::where('id', $id)->delete();
            $title = '';

            if (Member::where(['id' => $this->getUserId(), 'formula_id' => $id])->exists()) {
                Member::where(['id' => $this->getUserId(), 'formula_id' => $id])->update(['formula_id' => 0]);
                $title = '暂无首推配方，请在口味库中设置';
            }

            // 删除后 口味也跟着删除
            return $this->_successful(['title' => $title]);
        }

        return $this->_error('UNKNOWN_FORMULA_ID');
    }

    // 设为首页
    public function setIndex(Request $request, $id)
    {
        if ($id < 1) {
            return $this->_error('PARAM_NOT_EMPTY');
        }
        $userId = $this->getUserId();
        $formula = Formula::where(['member_id' => $userId, 'id' => $id])->first();

        if ($formula) {
            Member::where('id', $userId)->update(['formula_id' => $id]);

            return $this->_successful(['title' => $formula['title'], 'likes' => $formula['likes']]);
        } else {
            return $this->_error('UNKNOWN_FORMULA_ID');
        }
    }

    // 用户信息
    public function info(Request $request)
    {
        $openId = $this->_openid; // $request->get('openid');

        if (empty($openId)) {
            return $this->_error('PARAM_NOT_EMPTY');
        }

        $info = Member::where('openid', $openId)
            ->select(['shop_id', 'openid', 'gender', 'country', 'province', 'city', 'username'])
            ->first();

        if ($info) {
            $info['session_key'] = $this->getMd5($info['openid']);
        }

        return $this->_successful($info);
    }

    // 新增用户
    public function insert(Request $request)
    {
        $openId = $request->get('openid');
        $nickName = $request->get('nickName');
        $avatarUrl = $request->get('avatarUrl');

        if (empty($openId) || empty($nickName)) {
            return $this->_error('PARAM_NOT_EMPTY');
        }

        if (Member::where(['openid' => $openId])->exists()) {
            Member::where(['openid' => $openId])->update([
                'username' => $nickName,
                'avatar' => $avatarUrl,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            Member::insert([
                'shop_id' => $this->_shopId,
                'username' => $nickName,
                'avatar' => $avatarUrl,
                'openid' => $openId,
                'gender' => $request->get('gender'),
                'country' => $request->get('country'),
                'province' => $request->get('province'),
                'city' => $request->get('city'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $this->_successful();
    }

    // 发表留言
    public function postMessage(Request $request)
    {
        $content = $nickName = $request->get('content');
        $type = $nickName = $request->get('type');
        $image = $nickName = $request->get('imgPath');

        if (empty($content)) {
            return $this->_error('PARAM_NOT_EMPTY', '内容不能为空');
        }

        // 存储图片
//        if (! empty($image)) {
//
//        }

        MemberMessage::insert([
            'member_id' => $this->getUserId(),
            'content' => $content,
            'image' => $image,
            'type' => $type,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $info = Member::where(['id' => $this->getUserId()])->first();

        $data = [
            'avatar' => $info['avatar'],
            'username' => $info['username'],
            'content' => $content,
            'image' => $image,
            'date' => date('n月j')
        ];

        // 返回发表内容。是否需要审核
        return $this->_successful($data);
    }

    // 留言列表
    public function messageList(Request $request)
    {
        $type = $nickName = $request->get('type');

        $total = MemberMessage::where('type', $type)->count();
        $isMore = false;
        $messages = [];

        if ($total) {
            $messages = DB::table('member_messages')
                ->join('members', 'member_messages.member_id', '=', 'members.id')
                ->select(['members.avatar', 'members.username', 'member_messages.content', 'member_messages.image', 'member_messages.created_at'])
                ->where('member_messages.type', $type)
                ->orderBy('member_messages.id', 'desc')
                ->offset($this->_offset)
                ->limit($this->_rows)
                ->get();

            $year = date('Y');

            foreach ($messages as $key => $message) {
                $created = strtotime($message->created_at);
                $messages[$key]->date = date('Y', $created) == $year ? date('n月j', $created) : date('Y年n月j', $created);
            }

            $isMore = count($messages) == $this->_rows ? 1 : 0;
        }

        $totalPage = ceil($total / $this->_rows);

        return $this->_success($messages, $isMore, $total, $this->_page, $totalPage);
    }

    /**
     * 图片上传
     * @param Request $request
     * @return array|mixed
     */
    public function uploadImg(Request $request) {
        if (! $request->file('imgPath')) {
            return $this->_error('PARAM_NOT_EMPTY', '请上传图片');
        }

        $filename = $request->file('imgPath')->getClientOriginalName();
        $mineType = substr($filename, strrpos($filename, '.'));

        $filePath = 'message/' . date('Ymd') . uniqid() . $mineType;

        $result = Storage::put(
            $filePath,
            file_get_contents($request->file('imgPath')->getRealPath())
        );

        if ($result) {
            return $this->_successful(['filePath' => 'uploads/' . $filePath]);
        } else {
            return $this->_error('PARAM_NOT_EMPTY', '文件上传失败');
        }
    }

    // 发送短信接口
    public function sendSms(Request $request)
    {
        $phone = $request->get('phone');

        if (empty($phone)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入手机号');
        }

        if (! preg_match('/^1[345789]\d{9}$/', $phone)) {
            return $this->_error('PARAM_NOT_EMPTY', '请输入正确的手机号码');
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
            return $this->_successful();
        } else {
            return $this->_error();
        }
    }

    // 绑定手机号接口
    public function bindPhone(Request $request)
    {
        $phone = $request->get('phone');
        $code = $request->get('code');

        if (empty($phone) || empty($code)) {
            return $this->_error('PARAM_NOT_EMPTY', '手机号或验证码不能为空');
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

            Member::where(['openid' => $this->_openid])->update(['telephone' => $phone]);

            // 同步近6小时订单数据
            (new CrontabController(new Request()))->queryOrderListByTel($phone, $this->getUserId());

            return $this->_successful();
        } else {
            return $this->_error('PARAM_NOT_EMPTY', '验证码错误');
        }
    }
}

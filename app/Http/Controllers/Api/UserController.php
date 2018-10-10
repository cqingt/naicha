<?php
namespace App\Http\Controllers\Api;

use App\Http\Models\Formula;
use App\Http\Models\Member;
use App\Http\Models\MemberCoupon;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends CommonController
{
    public function index()
    {
        $userInfo = Member::find($this->getUserId());
        $title = '';

        if ($userInfo->formula) {
            $title = $userInfo->formula->title;
        }

        $formulaId = Member::where(['id' => $this->getUserId()])->pluck('formula_id');

        return $this->_successful(['title' => $title, 'formula_id' => $formulaId]);
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
            ->select(['id', 'order_sn', 'price',  'created_at', 'status'])
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
            $order['num'] =  count($details);

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
            $data[$detail['package_num']][] = $detail['goods_name'];
        }

        foreach ($data as $num => $datum) {
            $item = [
                'member_id'   => $order['member_id'],
                'order_id'    => $order['id'],
                'shop_id'     => $order['shop_id'],
                'package_num' => $num,
                'title'       => implode('+', $datum),
                'updated_at'  => $currentTime,
                'created_at'  => $currentTime
            ];

            if (! Formula::where(['order_id' => $order['id'], 'package_num' => $num])->exists()) {
                Formula::insert($item);
            }
        }

        return $this->_successful();
    }

    // 口味
    public function tastes($return = false)
    {
        $userId = $this->getUserId();

        $total =  Formula::where('member_id', $userId)->count();

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

        $memberCoupons = MemberCoupon::where(['member_id' => $userId, 'used' => 0])
            ->orderBy('id', 'desc')
            ->get();

        $data = [];

        foreach ($memberCoupons as $key => $memberCoupon) {
            $coupon = $memberCoupon->coupon;
            $data[$key]['id'] = $memberCoupon['id'];
            $data[$key]['coupon_id'] = $coupon['id'];
            $data[$key]['title'] = $coupon['title'];
            $data[$key]['deadline'] = date('Y-m-d', strtotime($coupon['stop_time']));
        }

        return $this->_successful($data);
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

            return $this->_successful(['title' => $formula['title']]);
        } else {
            return $this->_error('UNKNOWN_FORMULA_ID');
        }
    }

    // 用户信息
    public function info(Request $request)
    {
        $openId = $request->get('openid');

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
}
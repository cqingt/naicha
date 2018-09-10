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

        return $this->_successful(['title' => $title]);
    }

    public function orders(Request $request)
    {
        $userId = $this->getUserId();

        $orders = Order::where('member_id', $userId)
            ->where('status', '>', 0)
            ->orderBy('id', 'desc')
            ->select(['id', 'order_sn', 'price',  'created_at'])
            ->limit(10)
            ->get();

        foreach ($orders as $key => &$order) {
            $details = $order->details;
            $detail = $details[0];

            $order['image'] = $detail['goods_image'];
            $order['title'] = $detail['goods_name'];
            $order['num'] =  count($details);
            unset($orders[$key]['details']);
        }

        return $this->_successful($orders);
    }

    // 加入口味
    public function joinTaste(Request $request, $id)
    {
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
    public function tastes()
    {
        $userId = $this->getUserId();

        $tastes = Formula::where('member_id', $userId)
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return $this->_successful($tastes);
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
            $data[$key]['deadline'] = $coupon['stop_time'];
        }

        return $this->_successful($data);
    }

    // 删除口味
    public function deleteTaste(Request $request, $id)
    {
        $userId = $this->getUserId();

        if (Formula::where(['member_id' => $id, 'user_id' => $userId])->exists()) {
            Formula::where('member_id', $id)->delete();

            return $this->_successful();
        }

        return $this->_error('UNKNOWN_FORMULA_ID');
    }

    // 设为首页
    public function setIndex(Request $request, $id)
    {
        $userId = $this->getUserId();

        if (Formula::where(['member_id' => $userId, 'id' => $id])->exists()) {
            Member::where('id', $userId)->update(['formula_id' => $id]);

            return $this->_successful();
        } else {
            return $this->_error('UNKNOWN_FORMULA_ID');
        }
    }
}
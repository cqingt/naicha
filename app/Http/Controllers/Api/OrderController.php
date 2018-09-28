<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/9/21
 * Time: 11:03
 */
namespace App\Http\Controllers\Api;

use App\Http\Models\Member;
use App\Http\Models\MemberCoupon;
use App\Http\Models\Shop;
use Illuminate\Http\Request;
use App\Http\Models\Goods;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use DB;

class OrderController extends CommonController
{
    // 创建订单
    public function create(Request $request)
    {
        $data = $request->get('data');

        if (empty($data)) {
            return $this->_error('UNKNOWN_ERROR', '请选择配料');
        }

        $date =  date('Y-m-d H:i:s');
        $cartArr = json_decode($data, true);
        $index = 1;
        $insertData = [];
        $temperatures = [];
        $orderPrice = 0; // 订单总价

        foreach ($cartArr as $data) {
            $temperature = $data['temperature'];
            $sugar = $data['sugar'];
            $weight = $data['weight'];
            $doubleId = (int)$data['double'];
            $goodsIds = $data['list'];
            $sugar && array_push($goodsIds, $sugar); // 有选择糖类
            $temperatures[$index] = $temperature ? : 'ice'; // 设置温度

            $goodsInfo = Goods::whereIn('id', $goodsIds)->select(['id', 'name', 'price', 'image'])->get();

            foreach ($goodsInfo as $goods) {
                $insertData[] = [
                    'goods_id'    => $goods['id'],
                    'goods_name'  => $goods['name'],
                    'goods_image' => $goods['image'],
                    'goods_num'   => $goods['id'] == $doubleId ? 2 : 1,
                    'goods_price' => $goods['price'],
                    'package_num' => $index,
                    'deploy'      => $sugar == $goods['id'] ? $weight : '',
                    'created_at'  => $date
                ];
                $goodsPrice = bcmul($goods['price'], $goods['id'] == $doubleId ? 2 : 1, 2);
                $orderPrice = bcadd($orderPrice, $goodsPrice, 2);
            }
            $index++;
        }

        DB::beginTransaction();
        try{

            Order::insert([
                'shop_id'        => $this->_shopId,
                'member_id'      => $this->getUserId(),
                'order_sn'       => $this->getOrderSn(),
                'price'          => $orderPrice,
                'original_price' => $orderPrice,
                'pay_type'       => 1,
                'status'         => 0,     // 待支付
                'created_at'     => $date,
                'temperature'    => $temperatures ? serialize($temperatures) : ''
            ]);

            $orderId = DB::getPdo()->lastInsertId();

            if (! $orderId) {
                throw new \Exception('订单新增失败');
            }

            foreach ($insertData as $item) {
                $item['order_id'] = $orderId;

                OrderDetail::insert($item);
            }

            DB::commit();

            return $this->_successful();
        } catch (\Exception $e){
            DB::rollback();//事务回滚

            return $this->error($e->getMessage());
        }
    }

    protected function getOrderSn()
    {
        do {
            $orderSn = date('YmdHis') . rand(1000, 9999);
        } while (Order::where('order_sn', $orderSn)->count());

        return $orderSn;
    }

    /**
     * 检测订单  返回店铺名，用户手机号，排队情况，优惠券列表
     * @return array
     */
    public function checkOrder(Request $request)
    {
        $shopName = Shop::where('id', $this->_shopId)->pluck('name');
        $userInfo = Member::where('id', $this->getUserId())->select('telephone', 'gender')->first();
        $now = time();
        $datetime = date('Y-m-d H:i:s');
        $orderPrice = $request->get('orderPrice');

        $position = Order::where(['shop_id' => $this->_shopId, 'status' => 1])->whereBetween('created_at', [strtotime('today'), $now])->count();

        $couponList = DB::table('member_coupons')
            ->join('coupons', 'member_coupons.coupon_id', '=', 'coupons.id')
            ->select(['coupons.id', 'coupons.title', 'coupons.match_price', 'coupons.reduced_price', 'coupons.stop_time'])
            ->where('member_coupons.member_id', $this->getUserId())
            ->where('member_coupons.used', '=', 0)
            ->where(function ($query) use($orderPrice) {
                $query->where('coupons.match_price', '<=', $orderPrice)
                    ->orWhere('coupons.match_price', '=', 0);
            })
            ->where('coupons.stop_time', '>=', $datetime)
            ->where('coupons.start_time', '<', $datetime)
            ->orderBy('coupons.reduced_price', 'desc')
            ->get();

        if (! empty($couponList)) {
            foreach ($couponList as $key => &$item) {
                $item = (array)$item;
                $item['deadline'] = date('Y-m-d', strtotime($item['stop_time']));
            }
        }

        $gender = '';
        if ($userInfo) {
            if ($userInfo['gender'] == 1) {
                $gender = '先生';
            } else if ($userInfo['gender'] == 2) {
                $gender = '小姐';
            }
        }

        $data = [
            'datetime' => date('Y-m-d H:i'),
            'shopName' => $shopName,
            'telephone' => $userInfo ? $userInfo['telephone'] : '',
            'gender' => $gender,
            'position' => $position,
            'coupons' => $couponList
        ];

        return $this->_successful($data);
    }
}
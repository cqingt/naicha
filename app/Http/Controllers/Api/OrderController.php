<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/9/21
 * Time: 11:03
 */
namespace App\Http\Controllers\Api;

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

    public function store(Request $request)
    {
        $data = $request->all();
        $temperature = $data['temperature'];
        $sugar = $data['sugar'];
        $weight = $data['weight'];
        $double = (int)$data['double'];
        $goodsId = $data['list'];
        $date =  date('Y-m-d H:i:s');

        if (empty($data)) {
            return $this->error('请选择商品下单');
        }

        $totalPrice = 0;
        $orderPrice = 0; // 订单总价
        $index = 1;
        $temperature = []; // 订单，每杯温度
        $insertData = [];

        foreach ($data as $key => $item) {
            $item['sugar'] && array_push($item['list'], $item['sugar']); // 有选择糖类
            $temperature[$index] = $item['temperature'] ? : 'hot'; // 设置温度

            $goodsInfo = Goods::whereIn('id', $item['list'])->select(['id', 'name', 'price', 'image'])->get();

            foreach ($goodsInfo as $goods) {
                $insertData[] = [
                    'goods_id'    => $goods['id'],
                    'goods_name'  => $goods['name'],
                    'goods_image' => $goods['image'],
                    'goods_num'   => $goods['id'] == $item['double'] ? 2 : 1,
                    'goods_price' => $goods['price'],
                    'package_num' => $index,
                    'deploy'      => $item['sugar'] == $goods['id'] ? $item['weight'] : '',
                    'created_at'  => $date
                ];
                $goodsPrice = bcmul($goods['price'], $goods['id'] == $item['double'] ? 2 : 1, 2);
                $orderPrice = bcadd($orderPrice, $goodsPrice, 2);
            }

            $index++;

            $totalPrice = bcadd($totalPrice,  $item['price'], 2);
        }

        if (0 != bccomp($totalPrice, $orderPrice, 2)) {
            //  echo $totalPrice, ',', $orderPrice;exit;
            return $this->error('订单价格有误');
        }
        //dd($insertData);exit;

        DB::beginTransaction();
        try{

            Order::insert([
                'shop_id'        => $request->user()->shop_id,
                'member_id'      => 0,
                'order_sn'       => $this->getOrderSn(),
                'price'          => $orderPrice,
                'original_price' => $orderPrice,
                'pay_type'       => 0,     // 店员下单
                'status'         => 1,     // 已支付
                'payed_at'       => $date,
                'created_at'     => $date,
                'user_id'        => $request->user()->id,
                'temperature'    => $temperature ? serialize($temperature) : ''
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
        } catch (\Exception $e){
            DB::rollback();//事务回滚

            return $this->error($e->getMessage());
        }

        return $this->success('下单成功');
    }
}
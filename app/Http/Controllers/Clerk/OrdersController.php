<?php

namespace App\Http\Controllers\Clerk;

use App\Http\Models\Category;
use App\Http\Models\Goods;
use App\Http\Models\Order;
use App\Http\Models\OrderDetail;
use App\Http\Models\Shop;
use Illuminate\Http\Request;
use DB;
use think\Response;

class OrdersController extends CommonController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $from = $request->get('from');
        $startTime = '';
        $stopTime = '';

        if ($from == 'tips') {
            $startTime = date('Y-m-d 00:00:00');
            $stopTime = date('Y-m-d H:i:s');
        }

        return view('clerk.orders.index', compact('from', 'startTime', 'stopTime'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::select(['id', 'volume', 'name'])->get();
        $newCategory = [];
        foreach ($categories as $category) {
            $newCategory[$category->id] = $category;
        }

        $products = Goods::withTrashed()->orderBy('category_id')->get();

        $data = [];

        foreach ($products as $product) {
            $data[$product->category_id]['volume'] = $newCategory[$product->category_id]['volume'];
            $data[$product->category_id]['items'][] = $product;
        }

        // 2级品类分组 id
        /*$categoryOne = [20, 21, 22, 23, 24, 25];
        $categoryTwo = [26, 27, 28, 29];
        $categoryThree = [30, 31, 32, 33];
        $categoryMilk = [7, 8, 9, 16, 17]; // 排斥柑橘类*/
//print_r($data);exit;
        return view('clerk.orders.create', compact(['products', 'data']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->get('data');
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
            //$temperature[$index] = $item['temperature'] ? : 'hot'; // 设置温度
            $item['temperature'] && array_push($item['list'], $item['temperature']); // 设置温度
            $goodsInfo = Goods::whereIn('id', $item['list'])->select(['id', 'name', 'price', 'image'])->get();
            $temperature[$index] = $item['temperature'] ? : '55'; // 设置温度

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
            echo $totalPrice, ',', $orderPrice;
            return $this->error('订单价格不符');
        }

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

    /**
     * 获取订单号
     * @return string
     */
    protected function getOrderSn()
    {
        do {
            $orderSn = date('YmdHis') . rand(1000, 9999);
        } while (Order::where('order_sn', $orderSn)->count());

        return $orderSn;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $orderInfo = $order->details;

        $order['status'] =  $this->getStatus($order['status']);
        $order['pay_type'] = $this->getPayType($order['pay_type']);
        $temps = [];
        $tags = config('web.temperature'); // 温度选择

//        if ($order['temperature']) {
//            $temps = unserialize($order['temperature']);
//            foreach ($temps as &$temp) {
//                $temp = $tags[$temp];
//            }
//        }

        $packages = [];

        foreach ($orderInfo as $item) {
            $packages[$item['package_num']][] = $item;
        }

        return view('clerk.orders.show', compact('order', 'orderInfo', 'packages', 'temps', 'tags'));
    }

    /**
     * 列表API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function records(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制
        $orderSn = $request->get('order_sn');
        $shopId = $request->get('shop_id');
        $status = $request->get('status');
        $payType = $request->get('pay_type');
        $startTime = $request->get('start_time');
        $stopTime = $request->get('stop_time');

        $orm = Order::query();

        $shopId && $orm->where('shop_id', '=', $shopId);
        $orderSn && $orm->where('order_sn', 'like', "{$orderSn}%");
        is_numeric($status) && $orm->where('status', '=', $status);
        is_numeric($payType) && $orm->where('pay_type', '=', $payType);
        $startTime && $orm->where('created_at', '>=', $startTime);
        $stopTime && $orm->where('created_at', '<=', $stopTime);

        $data = $orm->orderBy('id', 'desc')->paginate($perPage);

        $items = $data->items();

        foreach ($items as &$item) {
            $item['shop_name'] = $item->shop->name;
            $item['member_name'] = $item->member ? $item->member->username : '';
            $item['status_type'] = $item['status'];
            $item['status'] = $this->getStatus($item['status']);
            $item['pay_type'] = $this->getPayType($item['pay_type']);
        }

        $result = [
            'code'  =>  0,
            'msg'   =>  '',
            'count' => $data->total(),
            'data'  => $items
        ];
        return response()->json($result);
    }

    public function cancel(Request $request, $id)
    {
        if (Order::find($id)->update(['status' => 6, 'operator' => $request->user()->id])) {
            // todo 退款
            return $this->success();
        } else {
            return $this->error();
        }
    }

    /**
     * 编辑订单
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\think\response\View
     * @throws \Exception
     */
    public function edit($id)
    {
        $orderInfo = Order::find($id)->toArray();
        $orderDetail = OrderDetail::where('order_id', $id)
            ->select(['id', 'goods_id', 'deploy', 'goods_num', 'goods_price', 'package_num'])
            ->get()
            ->toArray();

        if ($orderInfo['status'] != 1) {
            throw new \Exception('订单状态异常');
        }

        $packages = [];

        foreach ($orderDetail as $item) {
            $packages[$item['package_num']][] = $item;

            if (in_array($item['goods_id'], [52,53,54,55])) {
                $orderInfo['temperature'];
            }
        }

        $orderInfo['details'] = $packages;

        if ($orderInfo['temperature']) {
            $orderInfo['temperature'] = unserialize($orderInfo['temperature']);
        }

        $cups = count($packages);
        $orderInfo = json_encode($orderInfo);

        // 各分类
        $categories = Category::select(['id', 'volume', 'name'])->get();
        $newCategory = [];
        foreach ($categories as $category) {
            $newCategory[$category->id] = $category;
        }

        $products = Goods::withTrashed()->orderBy('category_id')->get();

        $data = [];

        foreach ($products as $product) {
            $data[$product->category_id]['volume'] = $newCategory[$product->category_id]['volume'];
            $data[$product->category_id]['items'][] = $product;
        }


        return view('clerk.orders.edit', compact(
            [
                'products', 'data', 'orderInfo', 'orderDetail', 'cups'
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Order::find($id)->update(['status' => 3, 'operator' => $request->user()->id])) {
            return $this->success();
        } else {
            return $this->error();
        }
    }

    // 编辑订单
    public function compile(Request $request)
    {
        $data    = $request->get('data');
        $orderId = $request->get('order_id');
        $date =  date('Y-m-d H:i:s');

        $orderInfo = Order::find($orderId);

        if (empty($orderInfo)){
            return $this->error('订单不存在');
        }

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
            $temperature[$index] = $item['temperature'] ? : '55'; // 设置温度
            $item['temperature'] && array_push($item['list'], $item['temperature']); // 设置温度
            $goodsInfo = Goods::whereIn('id', $item['list'])->select(['id', 'name', 'price', 'image'])->get();

            foreach ($goodsInfo as $goods) {
                $insertData[] = [
                    'order_id'    => $orderId,
                    'goods_id'    => $goods['id'],
                    'goods_name'  => $goods['name'],
                    'goods_image' => $goods['image'],
                    'goods_num'   => 1,
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
            echo '价格有误：' . $totalPrice, ',', $orderPrice;
            return $this->error('订单价格不符');
        }

        $difference = bcsub($totalPrice, $orderInfo['price'], 2); // 差额，负数表示平台需给用户付款，整数表示用户给平台付款

        DB::beginTransaction();
        try{
            $orderResult = Order::where('id', $orderId)->update(
                [
                    'price'          => $orderPrice,
                    'original_price' => $orderPrice,
                    'difference'     => $difference,
                    'temperature'    => $temperature ? serialize($temperature) : ''
                ]
            );

            $detailResult = OrderDetail::where('order_id', $orderId)->delete();

            if (! $orderResult || ! $detailResult ) {
                throw new \Exception('订单修改失败');
            }

            foreach ($insertData as $item) {
                OrderDetail::insert($item);
            }

            DB::commit();
        } catch (\Exception $e){
            DB::rollback();//事务回滚

            return $this->error($e->getMessage());
        }

        return $this->success(['difference' => $difference]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 订单状态
     * @param $status
     * @return mixed
     */
    protected function getStatus($status)
    {
        return config('web.order_status')[$status];
    }

    /**
     * 支付方式
     * @param $type
     * @return mixed
     */
    protected function getPayType($type)
    {
        return config('web.pay_type')[$type];
    }
}

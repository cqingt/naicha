<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Order;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

        $packages = [];

        foreach ($orderInfo as $item) {
            $packages[$item['package_num']][] = $item;
        }

        return view('admin.orders.show', compact('order', 'orderInfo', 'packages'));
    }

    /**
     * 列表API
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $perPage = $request->get('limit'); // 每页数量由首页控制

        $data = Order::orderBy('id', 'desc')->paginate($perPage);

        $items = $data->items();

        foreach ($items as &$item) {
            $item['shop_name'] = $item->shop->name;
            $item['member_name'] = $item->member->username;
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

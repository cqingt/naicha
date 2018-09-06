<?php

namespace App\Http\Controllers\Clerk;

use Illuminate\Http\Request;
use App\Http\Controllers\Clerk\CommonController;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Order;

class IndexController extends CommonController
{
    // 日期筛选
    protected $dates = [
        'today'         => '今日',
        'yesterday'     => '昨日',
        'current_week'  => '本周',
        'last_week'     => '上周',
        'current_month' => '本月',
        'last_month'    => '上月',

    ];

    // 日期对应时间戳
    protected $timeRule = [
        'today'         => ['today', 'tomorrow'],
        'yesterday'     => ['yesterday', 'today'],
        'current_week'  => ['this week 00:00:00', 'next week 00:00:00'],
        'last week'     => ['last week 00:00:00', 'this week 00:00:00'],
        'current_month' => ['first Day of this month 00:00:00', 'first Day of next month 00:00:00'],
        'last month'    => ['first Day of last month 00:00:00', 'first Day of this month 00:00:00'],
    ];

    public function index()
    {
        return view('clerk.layouts.index');
    }

    /**
     * 消息提醒
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listen(Request $request)
    {
        $user = $request->user();
        $between = [date('Y-m-d'), date('Y-m-d H:i:s')];

        $total = Order::where(['shop_id' => $user->shop_id, 'status' => 1])->whereBetween('created_at', $between)->count();

        if ($total) {
            return $this->success(['total' => $total]);
        } else {
            return $this->error();
        }
    }

    public function data(Request $request)
    {
        $start = $request->get('start_time');
        $stop = $request->get('stop_time');
        $dates = $this->dates;
        $currentDate = '';

        // 当用时间范围查找时，显示
        if ($start || $stop) {
            $dateBetween = [$start ?: date('Y-m-d '), $stop ?: date('Y-m-d H:i:s')];
            $showTxt = '区间';
        } else {
            $currentDate = $request->get('date_type') ?: 'today';
            $dateBetween = $this->getDate($currentDate);
            $showTxt = $dates[$currentDate];
        }

        // 统计数据
        $todo = $this->getTodo($dateBetween);
        $done = $this->getDone($dateBetween);
        $orders = $this->getOrders($dateBetween, true);
        $order = $this->getOrders($dateBetween);;
        $amounts = $this->getAmount($dateBetween, true);
        $amount = $this->getAmount($dateBetween);

        $assign = [
            'start', 'stop', 'dates', 'currentDate', 'todo', 'done', 'orders', 'order', 'amounts', 'amount', 'showTxt'
        ];

        return view('clerk.index.data', compact($assign));
    }

    /**
     * 获取时间范围
     * @param $date
     * @return array
     */
    protected function getDate($date)
    {
        if (! isset($this->timeRule[$date])) {
            $date = 'today';
        }

        $start = $this->timeRule[$date][0];
        $stop  = $this->timeRule[$date][1];

        return [date('Y-m-d H:i:s', strtotime($start)), date('Y-m-d H:i:s', strtotime($stop))];
    }

    // 下单数
    protected function getOrders($condition, $total = false)
    {
        $user = Auth::user();

        // 已处理
        if ($total) {
            return Order::where(['shop_id' => $user->shop_id, 'user_id' => $user->id])->count();
        }

        // 待处理
        return Order::where(['shop_id' => $user->shop_id, 'user_id' => $user->id])->whereBetween('created_at', $condition)->count();
    }

    // 下单金额
    protected function getAmount($condition, $total = false)
    {
        $user = Auth::user();
        if ($total) {
            return Order::where(['shop_id' => $user->shop_id, 'user_id' => $user->id])->whereIn('status', [1, 2, 3])->sum('price');
        }

        return Order::where(['shop_id' => $user->shop_id, 'user_id' => $user->id])->whereIn('status', [1, 2, 3])->whereBetween('created_at', $condition)->sum('price');
    }

    // 待做的订单数
    protected function getTodo($condition, $total = false)
    {
        $user = Auth::user();

        return Order::where(['shop_id' => $user->shop_id, 'status' => 1])->whereBetween('created_at', $condition)->count();
    }


    // 已处理的订单数
    protected function getDone($condition)
    {
        $user = Auth::user();

        return Order::where(['shop_id' => $user->shop_id, 'operator' => $user->id, 'status' => 3])->count();
    }
}

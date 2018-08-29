<?php

namespace App\Http\Controllers\Clerk;

use Illuminate\Http\Request;
use App\Http\Controllers\Clerk\CommonController;

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
        $shops = 1;
        $shop =2;
        $members = 3;
        $member = 4;
        $orders = 5;
        $order = 6;
        $amounts = 7;
        $amount = 8;

        $assign = [
            'start', 'stop', 'dates', 'currentDate', 'shops', 'shop', 'members', 'member', 'orders', 'order', 'amounts', 'amount', 'showTxt'
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

    protected function getOrders($condition, $total = false)
    {
        if ($total) {
            return Order::whereIn('status', [1, 2, 3])->count();
        }

        return Order::whereIn('status', [1, 2, 3])->whereBetween('created_at', $condition)->count();
    }
}

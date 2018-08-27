<?php
/**
 * 数据统计
 */

namespace App\Http\Controllers\Admin;

use App\Http\Models\Member;
use App\Http\Models\Order;
use App\Http\Models\Shop;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DataController extends Controller
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

    public function index(Request $request)
    {
        $start = $request->get('start_time');
        $stop = $request->get('stop_time');
        $dates = $this->dates;
        $currentDate = '';

        // 当用时间范围查找时，显示
        if ($start || $stop) {
            $dateBetween = [$start ? : date('Y-m-d '), $stop ? : date('Y-m-d H:i:s')];
            $showTxt = '区间';
        } else {
            $currentDate = $request->get('date_type') ? : 'today';
            $dateBetween = $this->getDate($currentDate);
            $showTxt = $dates[$currentDate];
        }

        // 统计数据
        $shops   = $this->getShops($dateBetween, true);
        $shop    = $this->getShops($dateBetween);
        $members = $this->getMembers($dateBetween, true);
        $member = $this->getMembers($dateBetween);
        $orders  = $this->getOrders($dateBetween, true);
        $order  = $this->getOrders($dateBetween);
        $amounts = $this->getAmount($dateBetween, true);
        $amount = $this->getAmount($dateBetween);

        $assign = [
            'start', 'stop', 'dates', 'currentDate', 'shops', 'shop', 'members', 'member', 'orders', 'order', 'amounts', 'amount', 'showTxt'
        ];

        return view('admin.data.index', compact($assign));
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

    protected function getShops($condition, $total = false)
    {
        if ($total) {
            return Shop::count();
        }

        return Shop::whereBetween('created_at',  $condition)->count();
    }

    protected function getOrders($condition, $total = false)
    {
        if ($total) {
            return Order::whereIn('status', [1, 2, 3])->count();
        }

        return Order::whereIn('status', [1, 2, 3])->whereBetween('created_at', $condition)->count();
    }

    protected function getAmount($condition, $total = false)
    {
        if ($total) {
            return Order::whereIn('status', [1, 2, 3])->sum('price');
        }

        return Order::whereIn('status', [1, 2, 3])->whereBetween('created_at', $condition)->sum('price');
    }

    protected function getMembers($condition, $total = false)
    {
        if ($total) {
            return Member::count();
        }

        return Member::whereBetween('created_at', $condition)->count();
    }
}

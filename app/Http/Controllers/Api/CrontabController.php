<?php
namespace App\Http\Controllers\Api;

use App\Http\Models\Member;
use App\Http\Models\Order;
use App\Http\Models\Goods;
use App\Http\Models\Formula;
use App\Http\Models\CrontabLog;
use App\Http\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use DB;
use Exception;

class CrontabController extends Controller
{
    protected $appId;
    protected $appKey;
    protected $baseUrl;

    protected $header; // 请求头信息

    protected $members = []; // 用户信息

    protected $postBackParameter = []; // 分页参数

    // 分页订单列表
    const ORDER_LIST_URL = 'pospal-api2/openapi/v1/ticketOpenApi/queryTicketPages';

    // 查询会员单据
    const ORDER_LIST_BY_UID = 'pospal-api2/openapi/v1/ticketOpenApi/queryCustomerHistoryTicketsByUid';

    // 根据会员ID 查询会员信息
    const QUERY_CUSTOMER_URL = 'pospal-api2/openapi/v1/customerOpenapi/queryByUid';

    // 根据手机号查询会员信息
    const QUERY_CUSTOMER_BYTEL = 'pospal-api2/openapi/v1/customerOpenapi/queryBytel';

    // 支付方式 转换
    protected $payment = [
        'payCode_103' => 2, //alipay
        'payCode_102' => 1, //wechat
        'payCode_1'   => 0, // cash
        'payCode_11'  => 2,
        'payCode_14'  => 1,
        'payCode_15'  => 2,
        'payCode_105' => 3,
        'payCode_107' => 4,
    ];

    protected $current; // 当前日期

    protected $startTime;

    protected $endTime;

    public function __construct(Request $request){
        $config = config('web');
        $this->appId = $config['yb_app_id'];
        $this->appKey = $config['yb_app_key'];
        $this->baseUrl = $config['yb_app_url'];

        $this->current = date('Y-m-d');

        // 查询一天订单
        if (isset($_GET['type']) && $_GET['type'] == 'day') {
            // 指定日期
            if (isset($_GET['date']) && $_GET['date']) {
                $this->startTime = date($_GET['date'] . ' 00:00:00');
                $this->endTime   = date($_GET['date'] . ' 23:59:59');
            } else {
                $this->startTime = date('Y-m-d 00:00:00');
                $this->endTime   = date('Y-m-d H:i:s');
            }

        } else {
            $this->startTime = date('Y-m-d H:00:00');
            $this->endTime = date('Y-m-d H:59:59');
        }

    }

    // 每小时查询
    public function getByHour()
    {
        while (true) {
            $isMore = $this->queryOrders();

            if (! $isMore) {
                break;
            }
        }
    }

    /**
     * 日志
     * @param $content
     * @param string $status
     */
    protected function addLog($content, $status = 'success')
    {
        CrontabLog::insert(
            [
                'status' => $status,
                'content' => $content,
                'created_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    protected function debug($data, $exit = 0) {
        if (isset($_GET['debug'])) {
            echo '<pre>';print_r($data);

            if ($exit) {
                exit;
            }
        }
    }
    protected function queryOrders()
    {
        $data = [
            "appId"     => $this->appId,
            "startTime" => $this->startTime,
            "endTime"   => $this->endTime,
        ];

        // 第二页必带参数
        if ($this->postBackParameter) {
            $data['postBackParameter'] = $this->postBackParameter;
        }

        $dataString = json_encode($data);

        // 签名
        $signature = strtoupper(md5($this->appKey . $dataString));

        // 设置header头信息
        $this->setHeader($signature);

        try {
            $result = $this->httpPost($this->baseUrl . self::ORDER_LIST_URL, $dataString, $this->header);
            $this->addLog('请求同步订单数据开始:' . var_export($result, true));
        } catch (Exception $e) {
            return $this->addLog('请求同步订单数据错误：' . var_export($e->getMessage(), true), 'error');
        }

        if ($result['status'] == 'success') {
            $data = $result['data'];
            $this->postBackParameter = isset($data['postBackParameter']) ? $data['postBackParameter'] : []; //分页参数

            $items = isset($data['result']) ? $data['result'] : [];
            $this->parseItems($items);

            return count($items) >= $result['data']['pageSize'];
        }

         return false;
    }

    /**
     * 获取数据库商品
     * @return array
     */
    protected function getGoods()
    {
        $goods = Goods::all();
        $newGoods = [];
        foreach ($goods as $it) {
            $newGoods[$it['name']] = [
                'id'    => $it['id'],
                'name'  => $it['name'],
                'image' => $it['image'],
                'deploy' => $it['deploy']
            ];
        }

        return $newGoods;
    }

    /**
     * 设置请求头信息
     * @param $signature
     */
    protected function setHeader($signature)
    {
        $this->header = [
            'User-Agent' => 'openApi',
            'Content-Type' => 'application/json; charset=utf-8',
            'accept-encoding' => 'gzip,deflate',
            'time-stamp' => $this->getMicrotime(),
            'data-signature' => $signature,
        ];
    }

    /**
     *  根据customerUid 查询手机号，再查找对应的 member.id
     * @param $customerUid
     * @return Member|int
     * @throws Exception
     */
    protected function queryMemberId($customerUid)
    {
        $data = [
            "appId"     => $this->appId,
            "customerUid" => $customerUid,
        ];

        $dataString = json_encode($data);

        // 签名
        $signature = strtoupper(md5($this->appKey . $dataString));
        $this->setHeader($signature);

        $result = $this->httpPost($this->baseUrl . self::QUERY_CUSTOMER_URL, $dataString, $this->header);

        if ($result['status'] == 'success' && isset($result['data']) && !empty($result['data'])) {
            $phone = $result['data']['phone'];

            $userInfo = Member::where(['telephone' => $phone])->first();

            if (! empty($userInfo)) {
                $userInfo = $userInfo->toArray();
                $this->members[$customerUid] = $userInfo['id'];

                // 更新uid
                Member::where(['telephone' => $phone])->update(['customerUid' => $customerUid]);

                return $userInfo['id'];
            } else {
                $this->addLog("根据{$customerUid}，查询到用户手机号：{$phone}，未匹配");
            }
        }

        return 0;
    }

    /**
     * 根据手机号查询并更新$customerUid
     * @param $phone
     * @return int
     * @throws Exception
     */
    public function updateCustomerUid($phone)
    {
        $customerUid = $this->queryByTel($phone);

        if ($customerUid) {
            $memberUid = Member::where(['telephone' => $phone])->pluck('customerUid');

            if ($customerUid && ! $memberUid) {
                return Member::where(['telephone' => $phone])->update(['customerUid' => $customerUid]);
            }
        }

        return false;
    }

    /**
     * @param $phone
     * @param $memberId
     * @return boolean
     * @throws Exception
     */
    public function queryOrderListByTel($phone, $memberId)
    {
        $this->addLog("用户绑定手机号: {$phone}，开始同步订单...");

        $customerUid = $this->queryByTel($phone);

        if (! $customerUid) {
            return false;
        }

        // 计算6小时内的
        $endTime = date('Y-m-d H:i:s');
        $startTime = date('Y-m-d H:i:s', strtotime('-16 hour'));

        $data = [
            "appId"       => $this->appId,
            "startTime"   => $startTime,
            "endTime"     => $endTime,
            'customerUid' => $customerUid,
        ];

        $dataString = json_encode($data);

        // 签名
        $signature = strtoupper(md5($this->appKey . $dataString));
        $this->setHeader($signature);

        $result = $this->httpPost($this->baseUrl . self::ORDER_LIST_BY_UID, $dataString, $this->header);

        if ($result['status'] == 'success' && isset($result['data']) && !empty($result['data'])) {
            $items = isset($result['data']['result']) ? $result['data']['result'] : [];

            return $this->parseItems($items, $memberId);
        }
    }

    /**
     * 根据手机号查询 $customerUid
     * @param $phone
     * @return  boolean
     * @throws Exception
     */
    protected function queryByTel($phone)
    {
        $data = [
            "appId"       => $this->appId,
            'customerTel' => $phone,
        ];

        $dataString = json_encode($data);

        // 签名
        $signature = strtoupper(md5($this->appKey . $dataString));
        $this->setHeader($signature);

        $result = $this->httpPost($this->baseUrl . self::QUERY_CUSTOMER_BYTEL, $dataString, $this->header);

        if ($result['status'] == 'success' && isset($result['data']) && !empty($result['data'])) {
            return $result['data'][0]['customerUid'];
        }

        return false;
    }


    /**
     * 解析订单数据
     * @param array $items
     * @param $memberId
     * @throws Exception
     */
    protected function parseItems(array $items, $memberId = 0) {
        $dbGoods = $this->getGoods();

        foreach ($items as $item) {
            $orderInfo = [];
            $customerUid = $item['customerUid'];

            // 过滤美团 饿了么订单
            if (isset($item['webOrderNo']) && $item['webOrderNo']) {
                continue;
            }

            if (! $customerUid) {
                continue;
            }

            if (! $memberId) {
                $memberId = 0;

                // 不重复插入
                if (Order::where('order_sn', $item['sn'])->pluck('id')) {
                    continue;
                }

                // 查询member表是否存在，不存在，查询接口，得到手机号，匹配
                if ($customerUid) {
                    if (in_array($customerUid, $this->members)) {
                        $memberId = $this->members[$customerUid];
                    } else {
                        $userInfo = Member::where(['customerUid' => $customerUid])->first();

                        if (!empty($userInfo)) {
                            $userInfo = $userInfo->toArray();
                            $memberId = $userInfo['id'];
                        } else {
                            $memberId = $this->queryMemberId($customerUid);
                        }
                    }
                }
            }

            if ($memberId < 1){
                continue; // 匹配不到用户id，则不导入
            }

            $payment = isset($item['payments'][0]) ? $item['payments'][0] : [];

            // 支付方式转换
            $payType = isset($this->payment[$payment['code']]) ? $this->payment[$payment['code']] : 0;

            $orderInfo['shop_id']    = 1;
            $orderInfo['order_sn']   = $item['sn'];
            $orderInfo['member_id']  = $memberId;
            $orderInfo['created_at'] = $item['datetime'];
            $orderInfo['payed_at']   = $item['datetime'];
            $orderInfo['pay_type']   = $payType;   // 支付方式
            $orderInfo['price']      = $payment['amount']; // 支付金额
            $orderInfo['original_price'] = $item['totalAmount'];
            $orderInfo['status'] = 3; // 已完成

            $orderDetail = []; // 订单详情
            $currentCup = 1;

            // 可能多杯
            foreach ($item['items'] as $cup) {
                $orderDetail[] = [
                    'goods_id'    => isset($dbGoods[$cup['name']]) ? $dbGoods[$cup['name']]['id'] : 0,
                    'goods_name'  => $cup['name'],
                    'goods_image' => isset($dbGoods[$cup['name']]) ? $dbGoods[$cup['name']]['image'] : '',
                    'order_id'    => 0,
                    'goods_num'   => 1,
                    'goods_price' => $cup['sellPrice'],
                    'package_num' => $currentCup,
                    'created_at'  => $item['datetime']
                ];

                // 每杯配料
                foreach ($cup['ticketitemattributes'] as $goods) {
                    $deploy = '';
                    $goodsName = '';

                    // 第三方传过来的数据格式是：蔗糖(五分糖)
                    if ($goods['attributeName'] != '黑糖奶盖'
                        && (
                            false !== stripos($goods['attributeName'], '蔗糖')
                            || false !== stripos($goods['attributeName'], '黑糖')
                        )
                    ) {
                        $deploy = mb_substr($goods['attributeName'], 2, null, 'utf-8');
                        $goodsName = mb_substr($goods['attributeName'], 0, 2, 'utf-8');
                    }

                    if ($goodsName) {
                        $goodsId = isset($dbGoods[$goodsName]) ? $dbGoods[$goodsName]['id'] : 0;
                    } else {
                        $goodsId = isset($dbGoods[$goods['attributeName']]) ? $dbGoods[$goods['attributeName']]['id'] : 0;
                    }

                    $orderDetail[] = [
                        'goods_id'    => $goodsId,
                        'goods_name'  => $goodsName ? : $goods['attributeName'],
                        'goods_image' => isset($dbGoods[$goods['attributeName']]) ? $dbGoods[$goods['attributeName']]['image'] : '',
                        'goods_num'   => 1,
                        'goods_price' => $goods['attributeValue'],
                        'package_num' => $currentCup,
                        'deploy'      => $deploy,
                        'created_at'  => $item['datetime'],
                    ];
                }

                $currentCup++;
            }

            //$this->addLog('同步订单新增订单表错误：' . var_export($orderDetail, true), 'error');
            DB::beginTransaction();

            try{
                Order::insert($orderInfo);
                $orderId = DB::getPdo()->lastInsertId();

                if (! $orderId) {
                    $this->addLog('同步订单新增订单表错误：' . var_export($orderInfo, true), 'error');
                    throw new \Exception('订单新增失败');
                }

                foreach ($orderDetail as $item) {
                    $item['order_id'] = $orderId;

                    $flag = OrderDetail::insert($item);

                    if (! $flag) {
                        $this->addLog('同步订单新增订单详情错误：' . var_export($item, true), 'error');
                        throw new \Exception('订单新增失败');
                    }
                }

                $this->setIndex($orderId); //设为首推

                DB::commit();
                $this->addLog('同步订单新增数据成功：' . $orderId);
            } catch (\Exception $e){
                DB::rollback();//事务回滚
                $this->addLog('同步订单新增数据错误：' . $e->getMessage() . ',line: ' . $e->getLine(), 'error');
            }
        }
    }

    /**
     * 设为首推
     */
    protected function setIndex($orderId)
    {
        $order = Order::find($orderId);
        $details = $order->details;
        $currentTime = date('Y-m-d H:i:s');

        $userInfo = Member::where('id' , $order['member_id'])->first();

        if ($userInfo &&  $userInfo['formula_id']) {
            return true; // 已设置首推
        }

        $data = [];
        foreach ($details as $detail) {
            $name = $detail['goods_name'];

            if ($detail['deploy']) {
                $name = $detail['goods_name'] . '(' . $detail['deploy'] . ')';
            }

            $data[$detail['package_num']][] = $name;
        }

        foreach ($data as $num => $goodsName) {
            $item = [
                'member_id' => $order['member_id'],
                'order_id' => $order['id'],
                'shop_id' => $order['shop_id'],
                'package_num' => $num,
                'title' => implode('+', $goodsName),
                'updated_at' => $currentTime,
                'created_at' => $currentTime
            ];

            if (! Formula::where(['order_id' => $orderId, 'package_num' => $num])->exists()) {
                Formula::insert($item);
                $formulaId = DB::getPdo()->lastInsertId();

                // 首单 设置首推
                if ($num == 1) {
                    Member::where('id', $order['member_id'])->update(['formula_id' => $formulaId]);
                }
            }
        }
    }

    /**
     * @param $url
     * @param $data
     * @param array $header
     * @return mixed|string
     * @throws Exception
     */
    protected function httpPost($url, $data, $header =[]){
        if(function_exists('curl_init')) {
            $urlArr = parse_url($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            if(is_array($header) && !empty($header)){
                $setHeader = array();
                foreach ($header as $k=>$v){
                    $setHeader[] = "$k:$v";
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeader);
            }

            if (strnatcasecmp($urlArr['scheme'], 'https') == 0) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
            }

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $output = curl_exec($ch);

            if(curl_errno($ch)){
                return curl_error($ch);
            }

            $info = curl_getinfo($ch);
            curl_close($ch);

            if (is_array($info) && $info['http_code'] == 200) {
                return json_decode($output, true, JSON_UNESCAPED_UNICODE, JSON_BIGINT_AS_STRING);
            } else {
                exit('请求失败（code）：' . $info['http_code']);
            }
        } else {
            throw new Exception('请开启CURL扩展');
        }
    }

    // 获取毫秒
    protected  function getMicrotime() {
        list($micro, $sec) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($micro) + floatval($sec)) * 1000);
    }
}
<?php
namespace App\Http\Controllers\Api;

use App\Http\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\Code;

class CommonController extends Controller
{
    // 分页，每页数量
    protected $_rows = 10;

    protected $_page = 1;

    protected $_offset = 0;

    public function __construct(Request $request)
    {
        $sessionId = $request->header('sessionId');
        $this->_page = $request->get('page', 1);
        $this->_offset = ($this->_page - 1) * $this->_rows;
    }

    /**
     *  带分页
     * @param $data
     * @param $isMore
     * @param $page
     * @param $total
     * @param $totalPage
     * @param array $option
     * @return array
     */
    protected function _success($data, $isMore, $total, $page = 1, $totalPage = 1, $option = [])
    {
        $result = [
            'code' => '200',
            'msg' => 'success',
            'data' => $data,
            'is_more' => $isMore,
            'current_page' => $page,
            'count' => $total,
            'total_page' => $totalPage
        ];
        if (! empty($option)) {
            $result = array_merge($result, $option);
        }
        return $result;
    }

    /**
     * 不带分页
     * @param array $data
     * @param int $code
     * @return array
     */
    protected function _successful($data = [], $code = 200)
    {
        return [
            'code' => $code,
            'msg' => 'success',
            'data' => $data
        ];
    }

    protected function _error($errorCode = 'UNKNOWN_ERROR', $errorMsg = '')
    {
        return Code::get($errorCode, $errorMsg);
    }

    protected function http_get($url, $header = [], $response = 'json') {
        if(function_exists('curl_init')) {
            $urlArr = parse_url($url);
            $ch = curl_init();
            if(is_array($header) && !empty($header)){
                $setHeader = array();
                foreach ($header as $k=>$v){
                    $setHeader[] = "$k:$v";
                }
                curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeader);
            }
            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_HEADER,0);
            if (strnatcasecmp($urlArr['scheme'], 'https') == 0) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
            }
            //执行并获取HTML文档内容
            $output = curl_exec($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);
            if (is_array($info) && $info['http_code'] == 200) {
                return $response == 'json' ? json_decode($output, true, JSON_UNESCAPED_UNICODE) : $output;
            } else {
                exit('请求失败（code）：' . $info['http_code']);
            }
        } else {
            throw new Exception('请开启CURL扩展');
        }
    }

    protected function getUserId()
    {
        return 1;
        $session = request('session_key');
        $sessionValue = session($session);

        if ($session && stripos($sessionValue, '|')) {
            $openId = explode('|',$sessionValue)[1];
            return Member::where('open_id', $openId)->pluck('id');
        } else {
            echo json_encode(
                [
                    'code' => 408,
                    'msg' => '请先登录账号',
                    'data' => []
                ]
            );
            exit;
        }
    }
}
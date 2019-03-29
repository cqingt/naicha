<?php
namespace App\Http\Controllers\Api;

use App\Http\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Library\Code;
use Cache;
use Illuminate\Http\Exception\HttpResponseException;
use Log;

class CommonController extends Controller
{
    // 分页，每页数量
    protected $_rows = 10;

    protected $_page = 1;

    protected $_offset = 0;

    protected $_shopId = 0; // 当前店铺

    protected $_openid = '';

    public function __construct(Request $request)
    {
        $this->_openid = $request->get('openid');

        $this->_shopId = $request->get('shopId');

        $this->_page = $request->get('page', 1);

        $this->_offset = ($this->_page - 1) * $this->_rows;

        if (empty($this->_shopId)) {
            // todo
        }

        $action = $request->route()->getAction();

        if (isset($action['controller'])) {

            //$controller = $action['controller'];
            list($routeControllerName, $routeActionName) = explode('@', $action['controller']);
            $routeControllerName = substr($routeControllerName, strrpos($routeControllerName , "\\") + 1);
            $routeControllerName = strtolower(str_replace('Controller', '', $routeControllerName));
            Log::info('class: '. $routeControllerName . ' action: ' . $routeActionName);
            // 非授权用户时，需要校验
            if (($routeControllerName == 'index' && $routeActionName=='getQrcode')
                ||($routeControllerName == 'index' && $routeActionName=='getOpenId')
                || ($routeControllerName == 'user' && $routeActionName=='insert')) {
                // 不做校验
            } else {
                $this->getUserId();
                //$this->_openid = $request->get('openid');// Cache::get($request->get('session_key'));

//                if (Cache::get(md5($this->_openid)) !== $request->get('session_key')) {
//                    echo json_encode(['code' => 399, 'msg' => '非法请求的接口', 'key' => Cache::get(md5($this->_openid)), 'key2' => $request->get('session_key')]); exit;
//                }
            }

            //$this->_openid = Cache::get($request->get('session_key'));

            // 重新设置，不过期
            //Cache::put(md5($this->_openid), $request->get('session_key'), 120);
        }
    }

    protected function getMd5($openid)
    {
        return md5($openid . config('web.api_mix'));
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

    function http_post($url, $data = [], $json = true, $response = 'json'){
        if(function_exists('curl_init')) {
            $urlArr = parse_url($url);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            if($json){
                $data = json_encode($data);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
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
        if ($this->_openid) {
            return Member::where(['openid' => $this->_openid])->pluck('id');
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

    /**
     * @param $imgsrc
     * @param $imgdst
     */
    protected function image_png_size_add($imgsrc, $imgdst)
    {
        list($width, $height, $type) = getimagesize($imgsrc);
        if ($height >= 1920) {
            $new_width = $width * 0.5;
            $new_height = $height * 0.5;
        } elseif ($height >= 1280) {
            $new_width = $width * 0.75;
            $new_height = $height * 0.75;
        } else {
            $new_width = $width;
            $new_height = $height;
        }

        switch ($type) {
            case 1:
                $giftype = $this->check_gifcartoon($imgsrc);
                if ($giftype) {
                    header('Content-Type:image/gif');
                    $image_wp = imagecreatetruecolor($new_width, $new_height);
                    $image = imagecreatefromgif($imgsrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagejpeg($image_wp, $imgdst, 75);
                    imagedestroy($image_wp);
                }
                break;
            case 2:
                header('Content-Type:image/jpeg');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefromjpeg($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 75);
                imagedestroy($image_wp);
                break;
            case 3:
                header('Content-Type:image/png');
                $image_wp = imagecreatetruecolor($new_width, $new_height);
                $image = imagecreatefrompng($imgsrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                imagejpeg($image_wp, $imgdst, 75);
                imagedestroy($image_wp);
                break;
        }
    }

    /**
     * desription 判断是否gif动画
     * @param sting $image_file图片路径
     * @return boolean t 是 f 否
     */


    /**
     * @param $image_file
     * @return bool
     */
    protected function check_gifcartoon($image_file)
    {
        $fp = fopen($image_file, 'rb');
        $image_head = fread($fp, 1024);
        fclose($fp);
        return preg_match("/" . chr(0x21) . chr(0xff) . chr(0x0b) . 'NETSCAPE2.0' . "/", $image_head) ? false : true;
    }

    protected function fptUpload($source_file, $filename)
    {
        $config = config('filesystems.disks');

        $ftp_server = $config['ftp']['host'];

        // 建立基础连接
        $conn_id = ftp_connect($ftp_server);

        // 使用用户名和口令登录
        $login_result = ftp_login($conn_id, $config['ftp']['username'], $config['ftp']['password']);

        // 检查是否成功
        if ((!$conn_id) || (!$login_result)) {
            echo "FTP connection has failed!";
            exit;
        }

        ftp_pasv($conn_id, true);// 打开被动模式传输

        $dir = '/messages/' . date('Ymd/') ;

        if (! file_exists($dir)) {
            @ftp_mkdir($conn_id, $dir);
        }

        ftp_chdir($conn_id, $dir);
        ftp_chmod($conn_id, 777, $dir);

        // 上传文件
        $upload = ftp_put($conn_id, $filename, $source_file, FTP_BINARY);

        // 关闭 FTP 流
        ftp_close($conn_id);

        // 检查上传结果
        if (! $upload) {
            return false;
        } else {
            return $dir . '/' . $filename;
        }
    }

}
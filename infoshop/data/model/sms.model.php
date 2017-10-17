<?php
/**
 * SEO
 *
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class smsModel extends Model
{

    public $key;

    public $smsapi_url;

    public function __construct()
    {
        parent::__construct('sms');
    }

    /**
     *
     * 批量发送短信
     * 
     * @param array $mobile
     *            手机号码
     * @param string $content
     *            短信内容
     * @param datetime $send_time
     *            发送时间
     * @param string $charset
     *            短信字符类型 gbk / utf-8
     * @param string $id_code
     *            唯一值 、可用于验证码
     */
    public function sendSms($content = '', $mobile = '', $passw = '', $send_time = '', $charset = 'gbk', $id_code = '')
    {
        
        //
        // 短信发送状态
        if (is_array($mobile)) {
            $mobile = implode(",", $mobile);
        }
        
        $content = $this->_safe_replace(iconv('utf-8', 'gbk', $content));
        $data = array(
            'method' => 'Submit',
            'account' => $GLOBALS['setting_config']['sms_type_user'],
            'password' => $GLOBALS['setting_config']['sms_type_pass'],
            'mobile' => $mobile,
            'content' => $content
        );
        $post = '';
        $index = 0;
        foreach ($data as $k => $v) {
            if ($index == 0) {
                $post .= $k . '=' . $v;
            } else {
                $post .= '&' . $k . '=' . $v;
            }
            $index ++;
        }
        
        // 短信URL地址
        $smsapi_senturl = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit'; // 填上发生地址
        $return = $this->_post($smsapi_senturl, 0, $post);
        
        $return = $this->xml_to_array($return);
        
        return $return;
    }

    public function xml_to_array($xml)
    {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i ++) {
                $subxml = $matches[2][$i];
                $key = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = $this->xml_to_array($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    /**
     * post数据
     * 
     * @param string $url            
     * @param int $limit            
     * @param string $post            
     * @param string $cookie
     *            cookie，字符串形式username='dalarge'&password='123456'
     * @param string $ip            
     * @param int $timeout            
     * @param bool $block            
     * @return string
     */
    private function _post($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = true)
    {
        $return = '';
        $url = str_replace('&amp;', '&', $url);
        $matches = parse_url($url);
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        $port = ! empty($matches['port']) ? $matches['port'] : 80;
        $siteurl = $this->_get_url();
        if ($post) {
            $out = "POST $path HTTP/1.1\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Referer: " . $siteurl . "\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen($post) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.1\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Referer: " . $siteurl . "\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if (! $fp)
            return '';
        
        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);
        
        if ($status['timed_out'])
            return '';
        while (! feof($fp)) {
            if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n"))
                break;
        }
        
        $stop = false;
        while (! feof($fp) && ! $stop) {
            $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
            $return .= $data;
            if ($limit) {
                $limit -= strlen($data);
                $stop = $limit <= 0;
            }
        }
        @fclose($fp);
        // var_export($return);
        // exit();
        // 部分虚拟主机返回数值有误，暂不确定原因，过滤返回数据格式
        // $return_arr = explode("\n", $return);
        // if(isset($return_arr[1])) {
        // $return = trim($return_arr[1]);
        // }
        // unset($return_arr);
        
        return $return;
    }

    /**
     * 获取当前页面完整URL地址
     */
    private function _get_url()
    {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? $this->_safe_replace($_SERVER['PHP_SELF']) : $this->_safe_replace($_SERVER['SCRIPT_NAME']);
        $path_info = isset($_SERVER['PATH_INFO']) ? $this->_safe_replace($_SERVER['PATH_INFO']) : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? $this->_safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . $this->_safe_replace($_SERVER['QUERY_STRING']) : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }

    /**
     * 安全过滤函数
     *
     * @param
     *            $string
     * @return string
     */
    private function _safe_replace($string)
    {
        $string = str_replace('%20', '', $string);
        $string = str_replace('%27', '', $string);
        $string = str_replace('%2527', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace("{", '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace('\\', '', $string);
        return urlencode($string);
    }
}
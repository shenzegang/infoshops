<?php
/**
 * 记录日志 
 *
 * @package    library
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @author	   InfolShop
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class Log
{

    const SQL = 'SQL';

    const ERR = 'ERR';

    private static $log = array();

    public static function record($message, $level = self::ERR)
    {
        $now = @date('Y-m-d H:i:s', time());
        switch ($level) {
            case self::SQL:
                self::$log[] = "[{$now}] {$level}: {$message}\r\n";
                break;
            case self::ERR:
                $log_file = BASE_DATA_PATH . '/log/' . date('Ymd', TIMESTAMP) . '.log';
                $url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
                $url .= " ( act={$_GET['act']}&op={$_GET['op']} ) ";
                $content = "[{$now}] {$url}\r\n{$level}: {$message}\r\n";
                file_put_contents($log_file, $content, FILE_APPEND);
                break;
        }
    }

    public static function read()
    {
        return self::$log;
    }
}
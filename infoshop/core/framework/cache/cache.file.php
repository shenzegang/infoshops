<?php
/**
 * file 缓存
 *
 * @copyright  Copyright (c) 2003-2015 B2B2C Inc. (http://www.infol.com.cn)
 * @license    http://www.infol.com.cn
 * @link       http://www.infol.com.cn
 * @author	   InfolShop
 * @since      File available since Release v1.1
 */
defined('CorShop') or exit('Access Invalid!');

class CacheFile extends Cache
{

    public function __construct($params = array())
    {
        $this->params['expire'] = C('cache.expire');
        $this->params['path'] = BASE_PATH . '/cache';
        $this->enable = true;
    }

    private function init()
    {
        return true;
    }

    private function isConnected()
    {
        return $this->enable;
    }

    public function get($key, $path = null)
    {
        $filename = realpath($this->_path($key));
        if (is_file($filename)) {
            return require ($filename);
        } else {
            return null;
        }
    }

    public function set($key, $value, $path = null, $expire = null)
    {
        $filename = $this->_path($key);
        if (false == write_file($filename, $value)) {
            return false;
        } else {
            return true;
        }
    }

    public function rm($key, $path = null)
    {
        $filename = realpath($this->_path($key));
        if (is_file($filename)) {
            @unlink($filename);
        } else {
            return false;
        }
        return true;
    }

    private function _path($key)
    {
        switch (strtolower($key)) {
            // case '':
            // $path = BASE_DATA_PATH.'/cache';
            // break;
            default:
                $path = BASE_DATA_PATH . '/cache';
                break;
        }
        return $path . '/' . $key . '.php';
    }
}
?>
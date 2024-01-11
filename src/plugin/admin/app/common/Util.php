<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2023-12-28
 * Time: 10:13:45
 * Info:
 */

namespace plugin\admin\app\common;

use think\facade\Db;
use Workerman\Timer;
use Workerman\Worker;

class Util
{

    /**
     * 类转换为url path
     *
     * @param $controller_class
     *
     * @return false|string
     */
    static function controllerToUrlPath($controller_class)
    {
        $key    = strtolower($controller_class);
        $action = '';
        if (strpos($key, '@')) {
            [$key, $action] = explode('@', $key, 2);
        }
        $prefix = 'plugin';
        $paths  = explode('\\', $key);
        if (count($paths) < 2) {
            return false;
        }
        $base = '';
        if (strpos($key, "$prefix\\") === 0) {
            if (count($paths) < 4) {
                return false;
            }
            array_shift($paths);
            $plugin = array_shift($paths);
            $base   = "/app/$plugin/";
        }
        array_shift($paths);
        foreach ($paths as $index => $path) {
            if ($path === 'controller') {
                unset($paths[$index]);
            }
        }
        $suffix = 'controller';
        $code   = $base.implode('/', $paths);
        if (substr($code, -strlen($suffix)) === $suffix) {
            $code = substr($code, 0, -strlen($suffix));
        }

        return $action ? "$code/$action" : $code;
    }

    /**
     * 转换为驼峰
     *
     * @param string $value
     *
     * @return string
     */
    public static function camel(string $value): string
    {
        static $cache = [];
        $key = $value;

        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $value = ucwords(str_replace(['-', '_'], ' ', $value));

        return $cache[$key] = str_replace(' ', '', $value);
    }

    /**
     * 转换为小驼峰
     *
     * @param $value
     *
     * @return string
     */
    public static function smCamel($value): string
    {
        return lcfirst(static::camel($value));
    }

    /**
     * 获取注释中第一行
     *
     * @param $comment
     *
     * @return false|mixed|string
     */
    public static function getCommentFirstLine($comment)
    {
        if ($comment === false) {
            return false;
        }
        foreach (explode("\n", $comment) as $str) {
            if ($s = trim($str, "*/\ \t\n\r\0\x0B")) {
                return $s;
            }
        }

        return $comment;
    }

}

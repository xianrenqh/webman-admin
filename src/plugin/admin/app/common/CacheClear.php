<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2024-01-10
 * Time: 11:06:46
 * Info: 清除对应缓存
 */

namespace plugin\admin\app\common;

use Shopwwi\LaravelCache\Cache;

class CacheClear
{

    /**
     * 清除角色列表缓存
     * @return void
     */
    public static function cacheRuleLists()
    {
        Cache::forget('cacheRuleLists');
    }

    /**
     * 清除系统配置缓存
     * @return void
     */
    public static function cacheSystemConfig()
    {
        Cache::forget('cacheSystemConfig');
    }

}

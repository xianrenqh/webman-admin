<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2024-01-08
 * Time: 11:53:37
 * Info:
 */

namespace plugin\admin\app\model;

class LogLogin extends Base
{

    /*
     * 写入记录
     */
    public static function addRecord($loginDevice)
    {
        if (empty($loginDevice)) {
            return false;
        }

        $update               = [];
        $update['admin_id']   = $loginDevice['admin_id'] ?? 0;
        $update['admin_name'] = $loginDevice['admin_name'] ?? '';
        $update['status']     = $loginDevice['status'] ?? 0;
        $update['ip_address'] = $loginDevice['ip_address'] ?? '';
        $update['country']    = $loginDevice['country'] ?? '';
        $update['province']   = $loginDevice['province'] ?? '';
        $update['city']       = $loginDevice['city'] ?? '';
        $update['isp']        = $loginDevice['isp'] ?? '';
        $update['desc']       = $loginDevice['desc'] ?? '';

        self::create($update);

        return true;
    }
}

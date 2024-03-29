<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2023-12-27
 * Time: 11:55:26
 * Info:
 */

namespace plugin\admin\app\controller;

use support\Model;
use support\Response;

class Base
{

    /**
     * @var Model
     */
    protected $model = null;

    /**
     * 无需登录及鉴权的方法
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 需要登录无需鉴权的方法
     * @var array
     */
    protected $noNeedAuth = [];

    /**
     * 数据限制
     * 例如当$dataLimit='personal'时将只返回当前管理员的数据
     * @var string
     */
    protected $dataLimit = null;

    /**
     * 数据限制字段
     */
    protected $dataLimitField = 'admin_id';

    /**
     * 返回格式化json数据
     *
     * @param int    $code
     * @param string $msg
     * @param array  $data
     *
     * @return Response
     */
    protected function json(int $code, string $msg = 'ok', array $data = []): Response
    {
        return json(['code' => $code, 'data' => $data, 'msg' => $msg]);
    }

    protected function success(string $msg = '成功', array $data = []): Response
    {
        return $this->json(200, $msg, $data);
    }

    protected function error(string $msg = '失败', array $data = []): Response
    {
        return $this->json(0, $msg, $data);
    }

}

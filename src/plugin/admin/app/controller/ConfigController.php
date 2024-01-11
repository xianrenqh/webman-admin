<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2024-01-10
 * Time: 11:12:47
 * Info:
 */

namespace plugin\admin\app\controller;

use support\Request;
use support\Response;
use support\exception\BusinessException;
use plugin\admin\app\model\Config;
use plugin\admin\app\common\CacheClear;

class ConfigController extends CrudController
{

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected $noNeedAuth = [];

    /**
     * @var Config
     */
    protected $model = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Config;
    }

    /**
     * 首页
     * @return Response
     */
    public function index(Request $request): Response
    {
        if ($request->method() === 'GET') {
            $type = $request->get('type');
            if (isset($type) && $type === 'editor_icon') {
                $ueditorIcon = config('plugin.admin.ueditor_icon');

                return $this->success('ok', $ueditorIcon);
            } else {
                return view('config/index');
            }
        }
    }

    /**
     * 查询
     *
     * @param Request $request
     *
     * @return Response
     * @throws BusinessException
     */
    public function select(Request $request): Response
    {
        [$where, $format, $limit, $field, $order] = $this->selectInput($request);
        $query = $this->doSelect($where, $field, $order);

        return $this->doFormat($query, $format, $limit);
    }

    /**
     * 编辑
     *
     * @param Request $request
     *
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $param = $request->post();
        if ( ! empty($param['ueditor_icon'])) {
            $param['ueditor_icon'] = implode(',', $param['ueditor_icon']);
        }
        foreach ($param as $key => $value) {
            $arr[$key] = $value;
            $value     = htmlspecialchars($value);
            Config::strict(false)->where(['name' => $key])->data(['value' => $value])->update();
        }
        //清除缓存
        CacheClear::cacheSystemConfig();

        return $this->success('保存成功');
    }

    /**
     * 查询数据，后置方法
     *
     * @param $items
     *
     * @return array|mixed
     */
    protected function afterQuery($items)
    {
        foreach ($items as $key => $v) {
            if (isset($v['name']) && $v['name'] === 'ueditor_icon') {
                $items[$key]['value'] = array_filter(explode(',', $v['value']));
            }
        }

        return $items;
    }

}

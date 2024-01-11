<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2023-12-27
 * Time: 14:18:20
 * Info:
 */

namespace plugin\admin\app\model;

class Rule extends Base
{

    /**
     * 获取权限规则列表
     *
     * @param $where    查询条件
     * @param $field    查询的字段
     *
     * @return void
     */
    public function getRuleLists($where = [], $field = "*")
    {
        $list = Rule::field($field)->where(function ($query) use ($where) {
            if ( ! empty($where)) {
                $query->where($where);
            }
        })->select()->toArray();

        return $list;
    }
}

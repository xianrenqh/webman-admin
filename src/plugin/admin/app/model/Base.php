<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2023-12-27
 * Time: 15:43:03
 * Info:
 */

namespace plugin\admin\app\model;

use think\Model;
use think\model\concern\SoftDelete;

class Base extends Model
{

    /**
     * 自动时间戳类型
     * @var string
     */
    protected $autoWriteTimestamp = true;

    /**
     * 添加时间
     * @var string
     */
    protected $createTime = 'create_time';

    /**
     * 更新时间
     * @var string
     */
    protected $updateTime = 'update_time';

    /**
     * 软删除
     */
    use SoftDelete;

    protected $defaultSoftDelete = 0;

    protected $deleteTime = false;
}

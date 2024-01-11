<?php

namespace plugin\admin\app\controller;

use support\Request;
use think\facade\Db;

class IndexController extends CrudController
{

    /**
     * 无需登录的方法
     * @var string[]
     */
    protected $noNeedLogin = ['index'];

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected $noNeedAuth = ['dashboard'];

    public function index()
    {
        clearstatcache();
        $admin = admin();
        if ( ! $admin) {
            return view('account/login');
        }

        return view('index/index');
    }

    /**
     * 仪表盘
     * @return void
     */
    public function dashboard()
    {
        return view('index/dashboard');
    }

}

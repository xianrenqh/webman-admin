<?php
/**
 * Created by PhpStorm.
 * User: 小灰灰
 * Date: 2023-12-27
 * Time: 14:44:18
 * Info:
 */

namespace plugin\admin\app\controller;

use plugin\admin\app\model\Admin;
use plugin\admin\app\model\LogLogin;
use support\exception\BusinessException;
use plugin\admin\app\common\Auth;
use support\Request;
use support\Response;
use Webman\Captcha\CaptchaBuilder;
use Webman\Captcha\PhraseBuilder;
use Shopwwi\LaravelCache\Cache;
use support\lib\Random;

class AccountController extends CrudController
{

    /**
     * 不需要登录的方法
     * @var string[]
     */
    protected $noNeedLogin = ['login', 'logout', 'captcha'];

    /**
     * 不需要鉴权的方法
     * @var string[]
     */
    protected $noNeedAuth = ['index', 'info', 'clearCache', 'editPwd'];

    /**
     * @var Admin
     */
    protected $model = null;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new Admin;
    }

    public function login(Request $request): Response
    {
        $this->checkDatabaseAvailable();

        $loginLogData = [];
        $ip_address   = get_client_ip();
        $ipToArea     = getIpToArea($ip_address);

        $captcha = $request->post('captcha', '');
        if (strtolower($captcha) !== session('captcha-login')) {
            return $this->json(0, '验证码错误');
        }
        $request->session()->forget('captcha-login');
        $username = $request->post('username', '');
        $password = $request->post('password', '');
        if ( ! $username) {
            return $this->json(0, '用户名不能为空');
        }
        $this->checkLoginLimit($username);
        $admin = Admin::where('username', $username)->find();

        $loginLogData['admin_id']   = $admin['id'];
        $loginLogData['admin_name'] = $admin['username'];
        $loginLogData['ip_address'] = $ip_address;
        $loginLogData['country']    = $ipToArea['country'];
        $loginLogData['province']   = $ipToArea['province'];
        $loginLogData['city']       = $ipToArea['city'];
        $loginLogData['isp']        = $ipToArea['isp'];

        if ( ! $admin || $admin->password != cmf_password($password, $admin->salt)) {
            $loginLogData['desc'] = '密码不正确：'.json_encode([
                    'username' => $username,
                    'password' => $password
                ], JSON_UNESCAPED_UNICODE);
            LogLogin::addRecord($loginLogData);

            return $this->json(0, '账户不存在或密码错误');
        }

        if ($admin->status != 1) {
            $loginLogData['desc'] = '账户已禁用：'.json_encode([
                    'username' => $username,
                    'password' => $password
                ], JSON_UNESCAPED_UNICODE);
            LogLogin::addRecord($loginLogData);

            return $this->json(0, '当前账户暂时无法登录');
        }
        $admin->login_time = time();
        $admin->login_ip   = $ip_address;
        $admin->save();
        $this->removeLoginLimit($username);

        $admin             = $admin->toArray();
        $session           = $request->session();
        $admin['password'] = md5($admin['password']);
        $session->set('admin', $admin);

        $loginLogData['status'] = 1;
        $loginLogData['desc']   = '登录成功：'.json_encode([
                'username' => $username,
                'password' => '******'
            ], JSON_UNESCAPED_UNICODE);
        LogLogin::addRecord($loginLogData);

        return $this->json(200, '登录成功', [
            'nickname' => $admin['nickname'],
            'token'    => $request->sessionId()
        ]);
    }

    /**
     * 退出
     *
     * @param Request $request
     *
     * @return Response
     */
    public function logout(Request $request): Response
    {
        $request->session()->delete('admin');

        return $this->json(200);
    }

    /**
     * 获取已登录管理员资料（可修改）
     * @return void
     */
    public function index(Request $request): Response
    {
        if ($request->method() === 'GET') {
            return view('account/userinfo');
        }
        $adminId = get_admin_id();
        $post    = $request->post();
        Admin::where('id', $adminId)->strict(false)->update($post);
        $admin = admin();
        foreach ($post as $key => $v) {
            $admin[$key] = $v;
        }
        $request->session('admin', $admin);

        return $this->success('操作成功');
    }

    /**
     * 获取登录信息
     *
     * @param Request $request
     *
     * @return Response
     */
    public function info(Request $request): Response
    {
        $admin = admin();
        if ( ! $admin) {
            return $this->json(0);
        }
        $info = [
            'id'            => $admin['id'],
            'username'      => $admin['username'],
            'nickname'      => $admin['nickname'],
            'avatar'        => $admin['avatar'],
            'email'         => $admin['email'],
            'mobile'        => $admin['mobile'],
            'isSupperAdmin' => Auth::isSupperAdmin(),
            'token'         => $request->sessionId(),
        ];

        return $this->success('ok', $info);
    }

    /**
     * 修改密码
     * @return Response
     */
    public function editPwd(Request $request): Response
    {
        if ($request->method() === 'GET') {
            return view('account/edit_pwd');
        }
        $id   = get_admin_id();
        $post = $request->post();
        if (empty($post['oldpwd'])) {
            return $this->error('原密码不能为空');
        }
        if (empty($post['newpwd'])) {
            return $this->error('新密码不能为空');
        }
        //查询管理员信息
        $findAdmin = Admin::find($id);
        if (empty($findAdmin)) {
            return $this->error('参数错误');
        }
        if ($findAdmin['password'] != cmf_password($post['oldpwd'], $findAdmin['salt'])) {
            return $this->error('原密码不正确');
        }
        $salt                  = Random::alnum(6);
        $findAdmin['password'] = cmf_password($post['newpwd'], $salt);
        $findAdmin['salt']     = $salt;
        $findAdmin->save();

        $admin             = admin();
        $admin['password'] = $findAdmin['password'];
        $admin['salt']     = $findAdmin['salt'];
        $request->session()->set('admin', $admin);

        return $this->success('密码修改成功');
    }

    /**
     * 清除缓存
     * @return Response
     */
    public function clearCache(): Response
    {
        Cache::flush();

        return $this->success('清除缓存成功');
    }

    /**
     * 验证码
     *
     * @param Request $request
     * @param string  $type
     *
     * @return Response
     */
    public function captcha(Request $request, string $type = 'login'): Response
    {
        $builder = new PhraseBuilder(4, 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ');
        $captcha = new CaptchaBuilder(null, $builder);
        $captcha->build(120);
        $request->session()->set("captcha-$type", strtolower($captcha->getPhrase()));
        $img_content = $captcha->get();

        return response($img_content, 200, ['Content-Type' => 'image/jpeg']);
    }

    /**
     * 检查登录频率限制
     *
     * @param $username
     *
     * @return void
     * @throws BusinessException
     */
    protected function checkLoginLimit($username)
    {
        $limit_log_path = runtime_path().'/login';
        if ( ! is_dir($limit_log_path)) {
            mkdir($limit_log_path, 0777, true);
        }
        $limit_file = $limit_log_path.'/'.md5($username).'.limit';
        $time       = date('YmdH').ceil(date('i') / 5);
        $limit_info = [];
        if (is_file($limit_file)) {
            $json_str   = file_get_contents($limit_file);
            $limit_info = json_decode($json_str, true);
        }

        if ( ! $limit_info || $limit_info['time'] != $time) {
            $limit_info = [
                'username' => $username,
                'count'    => 0,
                'time'     => $time
            ];
        }
        $limit_info['count']++;
        file_put_contents($limit_file, json_encode($limit_info));
        if ($limit_info['count'] >= 5) {
            throw new BusinessException('登录失败次数过多，请5分钟后再试');
        }
    }

    /**
     * 解除登录频率限制
     *
     * @param $username
     *
     * @return void
     */
    protected function removeLoginLimit($username)
    {
        $limit_log_path = runtime_path().'/login';
        $limit_file     = $limit_log_path.'/'.md5($username).'.limit';
        if (is_file($limit_file)) {
            unlink($limit_file);
        }
    }

    protected function checkDatabaseAvailable()
    {
        if ( ! config('plugin.admin.thinkorm')) {
            throw new BusinessException('请重启webman');
        }
    }

}

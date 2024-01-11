<?php

use plugin\admin\app\model\User;
use plugin\admin\app\model\Admin;
use plugin\admin\app\model\AdminRole;

/**
 * 当前管理员id
 * @return integer|null
 */
function get_admin_id(): ?int
{
    return session('admin.id');
}

function get_admin_group_id(): ?int
{
    return session('admin.id');
}

/**
 * 当前管理员
 *
 * @param null|array|string $fields
 *
 * @return array|mixed|null
 */
function admin($fields = null)
{
    refresh_admin_session();
    if ( ! $admin = session('admin')) {
        return null;
    }
    if ($fields === null) {
        return $admin;
    }
    if (is_array($fields)) {
        $results = [];
        foreach ($fields as $field) {
            $results[$field] = $admin[$field] ?? null;
        }

        return $results;
    }

    return $admin[$fields] ?? null;
}

/**
 * 刷新当前管理员session
 *
 * @param bool $force
 *
 * @return void
 */
function refresh_admin_session(bool $force = false)
{
    $admin_session = session('admin');
    if ( ! $admin_session) {
        return null;
    }
    $admin_id = $admin_session['id'];
    $time_now = time();
    // session在2秒内不刷新
    $session_ttl              = 2;
    $session_last_update_time = session('admin.session_last_update_time', 0);
    if ( ! $force && $time_now - $session_last_update_time < $session_ttl) {
        return null;
    }
    $session = request()->session();
    $admin   = Admin::find($admin_id);
    if ( ! $admin) {
        $session->forget('admin');

        return null;
    }
    $admin                     = $admin->toArray();
    $admin['password']         = md5($admin['password']);
    $admin_session['password'] = $admin_session['password'] ?? '';
    if ($admin['password'] != $admin_session['password']) {
        $session->forget('admin');

        return null;
    }
    // 账户被禁用
    if ($admin['status'] != 1) {
        $session->forget('admin');

        return;
    }
    $admin['roles'] = AdminRole::where('admin_id', $admin_id)->column('role_id');

    $admin['session_last_update_time'] = $time_now;
    $session->set('admin', $admin);
}

/**
 * CMF密码加密方法
 *
 * @param string $pw       要加密的原始密码
 * @param string $authCode 加密字符串,salt
 *
 * @return string
 */
if ( ! function_exists('cmf_password')) {
    function cmf_password($pw, $authCode = 'huicmf_webman_new')
    {
        $result = md5('#####'.md5($pw).$authCode);

        return $result;
    }
}

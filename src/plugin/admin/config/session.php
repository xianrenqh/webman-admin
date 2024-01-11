<?php
/**
 * This file is part of webman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author    walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link      http://www.workerman.net/
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Webman\Session\FileSessionHandler;
use Webman\Session\RedisSessionHandler;
use Webman\Session\RedisClusterSessionHandler;

return [

    'type' => 'file', // or redis or redis_cluster

    'handler' => FileSessionHandler::class,

    'config' => [
        'file'          => [
            'save_path' => runtime_path().'/sessions',
        ],
        'redis'         => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'auth'     => '',
            'timeout'  => 2,
            'database' => '',
            'prefix'   => 'redis_session_',
        ],
        'redis_cluster' => [
            'host'    => ['127.0.0.1:7000', '127.0.0.1:7001', '127.0.0.1:7001'],
            'timeout' => 2,
            'auth'    => '',
            'prefix'  => 'redis_session_',
        ]
    ],

    'session_name'          => 'PHPSID',
    'auto_update_timestamp' => true,   // 这里设置为true自动更新session
    'lifetime'              => 1 * 24 * 60 * 60, // 这里设置session过期时间
    'cookie_lifetime'       => 30 * 24 * 60 * 60, // cookie过期时间设置长一点，因为自动更新session不会自动续期cookie
    'cookie_path'           => '/',
    'domain'                => '',
    'http_only'             => true,
    'secure'                => false,
    'same_site'             => '',
    'gc_probability'        => [1, 1000],
];

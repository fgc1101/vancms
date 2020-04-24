<?php
include __DIR__ . '/vendor/autoload.php'; // 引入 composer 入口文件

use EasyWeChat\Foundation\Application;

$options = [
    'debug'  => true,
    'app_id' => 'wxd536b1080c588631',
    'secret' => '9334ee4e6dbddc8c7c1037a640429fd5',
    'token'  => 'fgc1994',

    // 'aes_key' => null, // 可选

    'log' => [
        'level' => 'debug',
        'file'  => '/tmp/easywechat.log', // XXX: 绝对路径！！！！
    ],

    //...
];

$app = new Application($options);

$response = $app->server->serve();

// 将响应输出
$response->send();

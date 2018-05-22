<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/21/2018
 * Time: 3:18 PM
 */
require_once __DIR__ . '/../vendor/autoload.php';
$config = [
    'wechat' => [
        'app_id' => 'wx37d4cfcb82e626e0',
        'app_secret' => 'f353d46f975b97f597e1a00c27575f62',
        'callback_url' => 'http://fan.35wi.com/client/member/wechat_login_callback',
    ],
];

$config_bzg = [
    'scope' => 'snsapi_login'
];
$oauth2 = new \Aguage\Oauth2\Oauth2($config);

// 第一步 获取code ，只有app端的不用，其他端都要，每个端获取code的url和参数都不一样，这个要能够配置
$auth = $oauth2->driver('wechat')->gateway('official')->redirect(['snsapi_userinfo']);

// 微信回调 得到code后的操作，如获取access_token，用户信息等
//$callback = $oauth2->driver('wechat')->gateway('web')->getUserInfo();



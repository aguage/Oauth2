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

//$auth = $oauth2->driver('wechat')->gateway('web')->redirect('snsapi_login');

$callback = $oauth2->driver('wechat')->gateway('web')->getAccessToken($config);
print_r($callback);


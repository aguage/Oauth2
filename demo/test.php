<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/21/2018
 * Time: 3:18 PM
 */
require_once '../vendor/autoload.php';
$config = [
    'wechat' => [
        'appid' => 'wx37d4cfcb82e626e0',              // APPID
        'mch_id' => '1503078331',             // 微信商户号
        'notify_url' => 'http://fan.35wi.com/client/member/wxpay_notify',
        'key' => 'd35246f975b97f597e1a00c27575f663',                // 微信支付签名秘钥
        'cert_client' => './apiclient_cert.pem',        // 客户端证书路径，退款时需要用到
        'cert_key' => './apiclient_key.pem',            // 客户端秘钥路径，退款时需要用到
    ],
];

$config_bzg = [
    'scope' => 'snsapi_userinfo'
];
$oauth2 = new \Aguage\Oauth2\Oauth2($config);

$a = $oauth2->driver('wechat')->gateway('app')->redirect($config_bzg);
print_r($a);


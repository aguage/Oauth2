<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/21/2018
 * Time: 3:27 PM
 */

namespace Aguage\Oauth2\Gateway\Wechat;




class AppGateway extends Wechat
{
    /**
     * The base url of WeChat API.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.weixin.qq.com/sns';
}
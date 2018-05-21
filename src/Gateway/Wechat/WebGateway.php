<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/21/2018
 * Time: 4:21 PM
 */

namespace Aguage\Oauth2\Gateway\Wechat;


use Aguage\Oauth2\Contract\GatewayInterface;

class WebGateway implements GatewayInterface
{

    /**
     * redirect url.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array|string $config_biz
     *
     * @return array|bool
     */
    public function redirect($config_biz)
    {
        // TODO: Implement redirect() method.
    }

    /**
     * close a order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array|string $config_biz
     *
     * @return array|bool
     */
    public function getAccessToken($config_biz)
    {
        // TODO: Implement getAccessToken() method.
    }

    /**
     * refresh token.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $out_trade_no
     *
     * @return array|bool
     */
    public function refreshToken($refreshToken)
    {
        // TODO: Implement refreshToken() method.
    }

    /**
     * get User.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $config_biz
     *
     * @return mixed
     */
    public function getUserInfo(array $config_biz)
    {
        // TODO: Implement getUserInfo() method.
    }
}
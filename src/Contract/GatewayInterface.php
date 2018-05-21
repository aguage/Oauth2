<?php

namespace Aguage\Oauth2\Contract;

interface GatewayInterface
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
    public function redirect($config_biz);

    /**
     * close a order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array|string $config_biz
     *
     * @return array|bool
     */
    public function getAccessToken($config_biz);

    /**
     * refresh token.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $out_trade_no
     *
     * @return array|bool
     */
    public function refreshToken($refreshToken);

    /**
     * get User.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $config_biz
     *
     * @return mixed
     */
    public function getUserInfo(array $config_biz);


}

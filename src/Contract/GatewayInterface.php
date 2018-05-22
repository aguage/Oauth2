<?php

namespace Aguage\Oauth2\Contract;


interface GatewayInterface
{

    /**
     * redirect url.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $scope
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(array $scope);

    /**
     * get User.
     *
     * @author yansongda <me@yansongda.cn>
     *
     *
     * @return mixed
     */
    public function getUserInfo();


}

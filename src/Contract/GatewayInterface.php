<?php
/**
 * Created by PhpStorm.
 * User: Aguage
 * Date: 5/18/2018
 * Time: 3:56 PM
 * Description:
 *
 * (c) yansongda <me@yansongda.cn>
 *
 * Modified By aguage <mr.huangyouzhi@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 */
namespace Aguage\Oauth2\Contract;


interface GatewayInterface
{

    /**
     * redirect url.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array $scope
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect(array $scope);

    /**
     * get User.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     *
     * @return mixed
     *
     * @throws \Aguage\Oauth2\Exception\Exception
     */
    public function getUserInfo();

    /**
     * get AccessToken.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     *
     * @return mixed
     *
     * @throws \Aguage\Oauth2\Exception\Exception
     */
    public function getAccessToken();
}

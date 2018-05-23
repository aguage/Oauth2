<?php
/**
 * Created by PhpStorm.
 * User: aguage
 * Date: 5/21/2018
 * Time: 4:21 PM
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

namespace Aguage\Oauth2\Gateway\Wechat;


use Aguage\Oauth2\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WebGateway extends Wechat
{

    /**
     * redirect url.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array|string $scope
     *
     * @return RedirectResponse
     */
    public function redirect(array $scope = ['snsapi_login'])
    {


        $baseUrl = 'https://open.weixin.qq.com/connect/qrconnect';
        $this->config['scope'] = implode(',',$scope);
        if (!in_array('snsapi_login',$scope)) {
            throw new InvalidArgumentException('暂时不支持的作用域');
        }

        //-------生成唯一随机串防CSRF攻击
        $state = $this->makeState();

        $param['appid'] = $this->config['app_id'];
        $param['redirect_uri'] = $this->config['callback_url'];
        $param['response_type'] = 'code';
        $param['scope'] = $this->config['scope'];
        $param['state'] = $state;

        $httpParam = http_build_query($param, '', '&');
        $url = $baseUrl . '?' . $httpParam . '#wechat_redirect';

        $response = new RedirectResponse($url);
        return $response->send();
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/21/2018
 * Time: 4:21 PM
 */

namespace Aguage\Oauth2\Gateway\Wechat;


use Aguage\Oauth2\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;


class WebGateway extends Wechat
{

    /**
     * redirect url.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array|string $scope
     *
     * @return RedirectResponse
     */
    //1组合url重定向到微信
    public function redirect(array $scope = ['snsapi_login'])
    {


        $baseUrl = 'https://open.weixin.qq.com/connect/qrconnect';
        $this->config['scope'] = implode(',',$scope);
        if ($scope != 'snsapi_login') {
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
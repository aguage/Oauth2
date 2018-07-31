<?php
/**
 * Created by PhpStorm.
 * User: aguage
 * Date: 5/22/2018
 * Time: 2:00 PM
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


use Aguage\Oauth2\Exception\Exception;
use Aguage\Oauth2\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class OfficialGateway extends Wechat
{

    /**
     * redirect url.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array $scope
     *
     * @return RedirectResponse
     */
    function redirect(array $scope)
    {

        $baseUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $this->config['scope'] = implode(',',$scope);
        if (in_array('snsapi_base',$scope)&&in_array('snsapi_userinfo',$scope)) {
            throw new InvalidArgumentException('暂时不支持的作用域');
        }

        //-------生成唯一随机串防CSRF攻击
        $state = $this->makeState();

        $param['appid'] = $this->config['app_id'];
        $param['redirect_uri'] = $this->config['callback_url'];
        $param['scope'] = $this->config['scope'];
        $param['response_type'] = 'code';
        if ($this->config['scope']=='snsapi_base'){
            $param['redirect_uri'] = $this->config['base_callback_url'];
            $param['scope'] = $this->config['scope'];
        }
        $param['state'] = $state;

        $httpParam = http_build_query($param, '', '&');
        $url = $baseUrl . '?' . $httpParam . '#wechat_redirect';

        $response = new RedirectResponse($url);
        return $response->send();
    }


    /**
     * get User.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getUserInfo()
    {

        // 1验证state
        // 2验证code 不带则用户取消授权
        // 3请求这个获取access_token接口后异常的信息有，code过期等。。
        $state = $this->request->getSession()->get('state');
        if ($state !== $this->request->get('state')) {
            throw new Exception('state错误', 1000001);
        }

        if (is_null($this->request->get('code'))) {
            throw new Exception('用户取消授权', 300002);
        }
        /**
         * { "access_token":"ACCESS_TOKEN",
         * "expires_in":7200,
         * "refresh_token":"REFRESH_TOKEN",
         * "openid":"OPENID",
         * "scope":"SCOPE" }
         */
        $data = $this->getAccessToken();

        $baseUrl = "https://api.weixin.qq.com/sns/userinfo";

        $param['access_token'] = $data['access_token'];
        $param['openid'] = $data['openid'];

        $httpParam = http_build_query($param, '', '&');


        /**
         * {    "openid":" OPENID",
         * " nickname": NICKNAME,
         * "sex":"1",
         * "province":"PROVINCE"
         * "city":"CITY",
         * "country":"COUNTRY",
         * "headimgurl":    "http://thirdwx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/46",
         * "privilege":[ "PRIVILEGE1" "PRIVILEGE2"     ],
         * "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
         * }
         */
        $responseJson = $this->get($baseUrl, $httpParam);

        $responseArray = json_decode($responseJson, true);

        if (isset($responseArray['errcode'])) {
            // 获取user_info接口异常情况  todo 这个要包装成oauth异常，还是不用呢？
            throw new Exception($responseArray['errmsg'], $responseArray['errcode']);
        }
        // 返回用户数据数组
        return $responseArray;
    }
}
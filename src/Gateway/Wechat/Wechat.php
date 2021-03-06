<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/18/2018
 * Time: 4:41 PM
 */

namespace Aguage\Oauth2\Gateway\Wechat;


use Aguage\Oauth2\Contract\GatewayInterface;
use Aguage\Oauth2\Exception\Exception;
use Aguage\Oauth2\Support\Config;
use Aguage\Oauth2\Traits\HasHttpRequest;
use Symfony\Component\HttpFoundation\Request;

abstract class Wechat implements GatewayInterface
{
    use HasHttpRequest;


    /**
     * The base url of WeChat API.
     *
     * @var string
     */
    protected $baseUrl = 'https://api.weixin.qq.com/sns/oauth2';

    /**
     * 获取assess_token
     * @var string
     */
    protected $gateway_access_token = 'access_token';

    /**
     * 刷新assess_token
     * @var string
     */
    protected $gateway_refresh_token = 'refresh_token';

    /**
     * 检查assess_token
     * @var string
     */
    protected $gateway_auth = 'auth';

    /**
     * 获取获取用户信息
     * @var string
     */
    protected $gateway_userinfo = 'userinfo';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var  \Aguage\Oauth2\Support\Config
     */
    protected $user_config;

    /**
     * The HTTP request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;


    /**
     * [__construct description].
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array $config
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(array $config, Request $request)
    {
        $this->user_config = new Config($config);

        $this->config = [
            'app_id' => $this->user_config->get('app_id', ''),
            'app_secret' => $this->user_config->get('app_secret', ''),
            'callback_url' => $this->user_config->get('callback_url', ''),

            // 其他可配参数
        ];

        $this->request = $request;

    }

    /**
     * redirect url.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array $scope
     * @return void
     */
    abstract public function redirect(array $scope);

    /**
     *  2通过Authorization Code获取Access Token
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @return array|bool
     */
    protected function makeState()
    {
        $state = sha1(uniqid(mt_rand(1, 1000000), true));
        $session = $this->request->getSession();
        if (is_callable([$session, 'put'])) {
            $session->put('state', $state);
        } elseif (is_callable([$session, 'set'])) {
            $session->set('state', $state);
        } else {
            return false;
        }
        return $state;
    }

    /**
     * get access_token.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @return array|bool
     *
     * @throws Exception
     */
    protected function getAccessToken()
    {
        $baseUrl = "https://api.weixin.qq.com/sns/oauth2/access_token";

        $param['appid'] = $this->config['app_id'];
        $param['secret'] = $this->config['app_secret'];
        $param['grant_type'] = "authorization_code";
        $param['code'] = $this->request->get('code');
        $param['grant_type'] = 'authorization_code';
        $httpParam = http_build_query($param, '', '&');

        /**
         * {
         * access_token: "10_MehlehkYhzHVDiNDR8PUjKbbhrLh3ddpks0E3l-GrDLQPHSTdy0UpFCEIOPQgc5v-c3THHHc26-60oAK8CJn4X8dgUI2ILCR2dUkBf5mj3Y",
         * expires_in: 7200,
         * refresh_token: "10_1oX5SNumedmPwd4h91v-MFhxPRIKZDwAAlEzBoi43dedMONvBcDJqj5iQ3_B16pwVboOWwUgwEM4XULNehUFLyH3GKcix6f0iZo1j_Es8yQ",
         * openid: "oEJiY1XU9tevAmReBIPK8X-C8xfg",
         * scope: "snsapi_userinfo",
         * unionid: "ofPjhwKcNXQITzwEYsMMpht53grg"
         * }
         */
        // 使用get请求access-token,返回json数据
        $responseJson = $this->get($baseUrl, $httpParam);

        $responseArray = json_decode($responseJson, true);
        if (isset($responseArray['errcode'])) {
            // 获取access_token接口异常情况  todo 这个要包装成oauth异常，还是不用呢？
            throw new Exception($responseArray['errmsg'], $responseArray['errcode']);
        }
        return $responseArray;
    }

    /**
     * refresh token.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param $refreshToken
     * @return void
     */
    public function refreshToken($refreshToken)
    {

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
    abstract public function getUserInfo();


}
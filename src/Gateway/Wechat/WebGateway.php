<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/21/2018
 * Time: 4:21 PM
 */

namespace Aguage\Oauth2\Gateway\Wechat;


use Aguage\Oauth2\Contract\GatewayInterface;
use Aguage\Oauth2\Exception\Exception;
use Aguage\Oauth2\Exception\InvalidArgumentException;
use Aguage\Oauth2\Support\Config;
use Aguage\Oauth2\Traits\HasHttpRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class WebGateway implements GatewayInterface
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
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $config
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function __construct(array $config,Request $request=null)
    {
        $this->user_config = new Config($config);

        $this->config = [
            'app_id'      => $this->user_config->get('app_id', ''),
            'app_secret'     => $this->user_config->get('app_secret', ''),
            'callback_url'  => $this->user_config->get('callback_url', ''),

            //  'sign_type'  => 'MD5',
            //  'notify_url' => $this->user_config->get('notify_url', ''),
            //  'trade_type' => $this->getTradeType(),
        ];

       $this->request ?: $this->createDefaultRequest();

    }

    /**
     * Create default request instance.
     *
     * @return Request
     */
    protected function createDefaultRequest()
    {
        $request = Request::createFromGlobals();
        $session = new Session();
        $request->setSession($session);
        return $request;
    }
    private $redirectBaseUrl = 'https://open.weixin.qq.com/connect/qrconnect';
    /**
     * redirect url.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array|string $config_biz
     *
     * @return array|bool
     */
    public function redirect($scope = 'snsapi_login')
    {
        $this->config['scope'] = $scope;
        if ($scope!='snsapi_login'){
            throw new InvalidArgumentException('暂时不支持的作用域');
        }

        //-------生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));

        $_SESSION['wx_state'] = $state;

        $url = $this->buildAuthUrlFromBase($this->redirectBaseUrl,$state);
        var_dump($url);
        exit;
        header("Location:" . $url);
        exit;

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
        $state = $this->request->getSession()->get('state');
        if ($state != $this->get('state')){
            throw new Exception('state错误');
        }

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $param['grant_type'] = "authorization_code";

        $param['appid'] = $this->config['app_id'];
        $param['secret'] = $this->config['app_secret'];

        $param['code'] = $this->request->get('code');
        $param['grant_type'] = 'authorization_code';
        $param = http_build_query($param, '', '&');
        $url = $url . "?" . $param;
         halt($url);
        return $this->get($url);
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

    //1获取Authorization Code
    public function getAuthCode()
    {


    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = http_build_query($this->getCodeFields($state), '', '&');

        return $url.'?'.$query.'#wechat_redirect';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {

        return [
            'appid' => $this->config['app_id'],
            'redirect_uri' => $this->config['callback_url'],
            'response_type' => 'code',
            'scope' => $this->config['scope'],
            'state' => $state ?: md5(time()),
        ];
    }
}
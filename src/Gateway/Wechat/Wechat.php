<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/18/2018
 * Time: 4:41 PM
 */

namespace Aguage\Oauth2\Gateway\Wechat;


use Aguage\Oauth2\Contract\GatewayInterface;
use Aguage\Oauth2\Support\Config;
use Aguage\Oauth2\Traits\HasHttpRequest;

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
     * [__construct description].
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->user_config = new Config($config);

        $this->config = [
            'app_id'      => $this->user_config->get('app_id', ''),
            'app_secret'     => $this->user_config->get('app_secret', ''),
            'call_back_url'  => $this->user_config->get('call_back_url', ''),
          //  'sign_type'  => 'MD5',
          //  'notify_url' => $this->user_config->get('notify_url', ''),
          //  'trade_type' => $this->getTradeType(),
        ];

    }

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
     *  2通过Authorization Code获取Access Token
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array|string $config_biz
     *
     * @return array|bool
     */
    public function getAccessToken($config_biz)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token";
        $param['grant_type'] = "authorization_code";

        $param['appid'] = $this->app_id;
        $param['secret'] = $this->app_secret;

        $param['code'] = $this->code;
        $param['grant_type'] = 'authorization_code';
        $param = http_build_query($param, '', '&');
        $url = $url . "?" . $param;
        // halt($url);
        return $this->getUrl($url);
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

        return array_merge([
            'appid' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'response_type' => 'code',
            'scope' => $this->formatScopes($this->scopes, $this->scopeSeparator),
            'state' => $state ?: md5(time()),
            'connect_redirect' => 1,
        ], $this->parameters);
    }








    /**
     * 4获取qq详细信息
     * @return bool|mixed
     * @throws WechatException
     */
    public function getUsrInfo()
    {
        /* if ($_GET['state'] != $_SESSION['wx_state']) {
             throw new WechatException('state检验失败！',300001);
        }*/// 注释这个代表是采用app发送的请求

        if (isset($_GET['code'])==false){
            throw new WechatException('用户取消授权',300002);
        }
        $this->code = $_GET['code'];

        $rzt = $this->getAccessToken();

        $data = json_decode($rzt,true);
        if (empty($data)||isset($data['errcode'])) {
            throw new WechatException('获取access_token失败');
        }
        $url = "https://api.weixin.qq.com/sns/userinfo";

        $param['access_token'] = $data['access_token'];
        $param['openid'] = $data['openid'];
        $param = http_build_query($param, '', '&');
        $url = $url . "?" . $param;
        $rzt = $this->getUrl($url);
        $rzt = json_decode($rzt);
        //halt($rzt);
        return $rzt;
    }


}
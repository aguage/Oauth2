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
     * @var string
     */
    protected $endpoint = 'https://api.mch.weixin.qq.com/';

    /**
     * @var string
     */
    protected $gateway_order = 'pay/unifiedorder';

    /**
     * @var string
     */
    protected $gateway_query = 'pay/orderquery';

    /**
     * @var string
     */
    protected $gateway_close = 'pay/closeorder';

    /**
     * @var string
     */
    protected $gateway_refund = 'secapi/pay/refund';

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
            'appid'      => $this->user_config->get('app_id', ''),
            'mch_id'     => $this->user_config->get('mch_id', ''),
           // 'nonce_str'  => $this->createNonceStr(),
          //  'sign_type'  => 'MD5',
          //  'notify_url' => $this->user_config->get('notify_url', ''),
          //  'trade_type' => $this->getTradeType(),
        ];

        if ($endpoint = $this->user_config->get('endpoint_url')) {
            $this->endpoint = $endpoint;
        }
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
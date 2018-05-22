<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/18/2018
 * Time: 3:30 PM
 */

namespace Aguage\Oauth2;

use Aguage\Oauth2\Support\Config;
use Aguage\Oauth2\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class Oauth2
{
    /**
     * @var \Aguage\Oauth2\Support\Config
     */
    private $config;

    /**
     * @var string
     */
    private $drivers;

    /**
     * @var \Aguage\Oauth2\Contract\GatewayInterface
     */
    private $gateways;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * construct method.
     *
     * @author JasonYan <me@yansongda.cn>
     *
     * @param array $config
     *
     * @param Request $request
     */
    public function __construct(array $config = [], Request $request = null)
    {
        $this->config = new Config($config);

        if ($request) {
            $this->setRequest($request);
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request ?: $this->createDefaultRequest();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        return $this;
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

    /**
     * set oauth2's driver.
     *
     * @author JasonYan <me@yansongda.cn>
     *
     * @param string $driver
     *
     * @return Oauth2
     */
    public function driver($driver)
    {
        if (is_null($this->config->get($driver))) {
            throw new InvalidArgumentException("Driver [$driver]'s Config is not defined.");
        }

        $this->drivers = $driver;

        return $this;
    }

    /**
     * set Oauth2's gateway.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $gateway
     *
     * @return \Aguage\Oauth2\Contract\GatewayInterface
     */
    public function gateway($gateway = 'web')
    {
        if (!isset($this->drivers)) {
            throw new InvalidArgumentException('Driver is not defined.');
        }

        $this->gateways = $this->createGateway($gateway);

        return $this->gateways;
    }

    /**
     * create oauth2's gateway name.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $gateway
     *
     * @return \Aguage\Oauth2\Contract\GatewayInterface
     */
    protected function createGateway($gateway)
    {

        if (!file_exists(__DIR__ . '/Gateway/' . ucfirst($this->drivers) . '/' . ucfirst($gateway) . 'Gateway.php')) {
            throw new InvalidArgumentException("Gateway [$gateway] is not supported.");
        }

        $gateway = __NAMESPACE__ . '\\Gateway\\' . ucfirst($this->drivers) . '\\' . ucfirst($gateway) . 'Gateway';

        return $this->build($gateway);
    }

    /**
     * build oauth2's gateway.
     *
     * @author JasonYan <me@yansongda.cn>
     *
     * @param string $gateway
     *
     * @return \Aguage\Oauth2\Contract\GatewayInterface
     */
    protected function build($gateway)
    {
        return new $gateway($this->config->get($this->drivers),$this->getRequest());
    }
}
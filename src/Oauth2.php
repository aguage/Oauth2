<?php
/**
 * Created by PhpStorm.
 * User: Aguage
 * Date: 5/18/2018
 * Time: 3:30 PM
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
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array $config
     *
     * @param Request $request
     */
    public function __construct(array $config = [], Request $request = null)
    {
        $this->config = new Config($config);

        if (!is_null($request)) {
            $this->setRequest($request);
        }
    }

    /**
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->request ?: $this->createDefaultRequest();
    }

    /**
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
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
     * @author aguage <mr.huangyouzhi@gmail.com>
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
     * @author aguage <mr.huangyouzhi@gmail.com>
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
     * @author aguage <mr.huangyouzhi@gmail.com>
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
     * @author aguage <mr.huangyouzhi@gmail.com>
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
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param string $gateway
     *
     * @return \Aguage\Oauth2\Contract\GatewayInterface
     */
    protected function build($gateway)
    {
        return new $gateway($this->config->get($this->drivers), $this->getRequest());
    }
}
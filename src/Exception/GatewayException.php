<?php
/**
 * Created by PhpStorm.
 * User: MaiShang
 * Date: 5/18/2018
 * Time: 3:35 PM
 */

namespace Aguage\Oauth2\Exception;


class GatewayException extends Exception
{
    /**
     * error raw data.
     *
     * @var array
     */
    public $raw = [];

    /**
     * [__construct description].
     *
     * @author JasonYan <me@yansongda.cn>
     *
     * @param string     $message
     * @param string|int $code
     */
    public function __construct($message, $code, $raw = [])
    {
        parent::__construct($message, intval($code));

        $this->raw = $raw;
    }
}
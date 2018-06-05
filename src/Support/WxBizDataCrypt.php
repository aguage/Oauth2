<?php

/**
 * 对微信小程序用户加密数据的解密示例代码.
 *
 * @copyright Copyright (c) 1998-2014 Tencent Inc.
 */


namespace Aguage\Oauth2\Support;

use Aguage\Oauth2\Exception\Exception;

class WXBizDataCrypt
{
    private $appid;
    private $sessionKey;

    /**
     * 构造函数
     * @param $sessionKey string 用户在小程序登录后获取的会话密钥
     * @param $appid string 小程序的appid
     */
    public function __construct($appid, $sessionKey)
    {
        $this->sessionKey = $sessionKey;
        $this->appid = $appid;
    }


    /**
     * 检验数据的真实性，并且获取解密后的明文.
     * @param $encryptedData string 加密的用户数据
     * @param $iv string 与用户数据一同返回的初始向量
     * @return string 解密后的数据
     * @throws Exception
     */
    public function decryptData($encryptedData, $iv)
    {
        if (strlen($this->sessionKey) != 24) {
            throw new Exception('encodingAesKey 非法', -40001);
        }
        $aesKey = base64_decode($this->sessionKey);


        if (strlen($iv) != 24) {
            throw new Exception('iv向量非法', -40002);
        }
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode($result);
        var_dump($dataObj);
        if ($dataObj == NULL) {
            throw new Exception('解密后得到的buffer非法', -40003);
        }
        if ($dataObj->watermark->appid != $this->appid) {
            throw new Exception('解密后得到的buffer非法', -40003);
        }
        $data = $result;

        return $data;

    }

}


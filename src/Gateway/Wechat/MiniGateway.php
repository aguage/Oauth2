<?php
/**
 * Created by PhpStorm.
 * User: aguage
 * Date: 5/22/2018
 * Time: 2:02 PM
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
use Aguage\Oauth2\Support\WxBizDataCrypt;

class MiniGateway extends Wechat
{

    /**
     * redirect url.
     *
     * @author aguage <mr.huangyouzhi@gmail.com>
     *
     * @param array $scope
     * @return void
     */
    function redirect(array $scope)
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
    public function getUserInfo()
    {
        //必须入三个参数code，encrypted_data，iv，以post方式

        if (is_null($this->request->get('code'))) {
            throw new Exception('用户取消授权', 300002);
        }

        $baseUrl = 'https://api.weixin.qq.com/sns/jscode2session';
        $param['appid'] = $this->config['app_id'];
        $param['secret'] = $this->config['app_secret'];
        $param['js_code'] = $this->request->get('code');
        $param['grant_type'] = "authorization_code";
        $httpParam = http_build_query($param, '', '&');

        // 使用get请求access-token,返回json数据
        $responseJson = $this->get($baseUrl, $httpParam);

        $responseArray = json_decode($responseJson, true);
        if (isset($responseArray['errcode'])) {
            // 获取access_token接口异常情况  todo 这个要包装成oauth异常，还是不用呢？
            throw new Exception($responseArray['errmsg'], $responseArray['errcode']);
        }

        /**
         *  'session_key' => string 'PAQ0MSv9PJglM+nxk7SYpA==' (length=24)
         * 'openid' => string 'oPfb05YmGfb81gdU-Z4e42s6WcAM' (length=28)
         * 0k27+nL+sboSOKaWPPJECY8sQMjUV4Ibb3rynqewiOvE78ihD8Tu1RgvXIlIk+tMCOwQDgeLphTkGEQ+QCSScn4ekXXFKfhhW0YuM8ORl3JZM8oHkYXQGAtCRaPMiViEirA4e7mXHJoPRAA+HZtGMPApwTQw0Yf6PojGaro3Wnqv+EblDEyjHIzawBgq+xMQkGjBKSUzkorfruqIawR04UFLENOxEHOye0E0M7kgX8jjhEqF22gsP8LnOJB9WrtmkYc4X6ahrBOZsy/JIu6DWrkN+plywRWlBb6orYZZc+7iZi7SxdsV8TohQVLpvgC6DIe9rWCkiR8hl9g55m5zRD/Nu9pUYJCHwIdy2aj8mzxv9ZQLLS7/vI+fyGFNlRjj/2XuhMHzpw3q1l/u/SPWP2XCPG9Ebn0h/FfqSl/qauJbRN4lqIAXM98Dgphbnjzajvksbz5irKCtFmjzLXcI7nMSXy08KZNcivINroORvyU=
         *
         * hPw0cNVuEv7N8Ea9jepg1Q==
         */
        // 解密用户数据
        $pc = new WxBizDataCrypt($this->config['app_id'], $responseArray['session_key']);
        $decryptData = $pc->decryptData($this->request->get('encrypted_data'), $this->request->get('iv'));

        /**
         * {"openId":"oPfb05YmGfb81gdU-Z4e42s6WcAM","nickName":"wentao","gender":1,"language":"zh_CN","city":"Quanzhou","province":"Fujian","country":"China","avatarUrl":"https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTKQPy0rX5Wt6RAj44fkqWGnUn6T0DffZUQ9TCpucIrYJibyOgLsN3GypFuptjgDVJJPJnXLib7BZqPg/132","watermark":{"timestamp":1528162182,"appid":"wx1229f2a9ca16f748"}}
         */
        // 返回用户数据数组
        return json_decode($decryptData, true);

    }


}
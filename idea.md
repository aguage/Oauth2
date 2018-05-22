## oauth2原理

1. 第一步：用户同意授权，获取code

2. 第二步：通过code换取网页授权access_token

3. 第三步：刷新access_token（如果需要）

4. 第四步：拉取用户信息

不同的厂商对Oauth2具体实现字段不是非常标准

### QQ互联
> pc网站
1. 获取Authorization Code

请求网止：https://graph.qq.com/oauth2.0/authorize

参数：response_type,client_id,redirect_uri,state,scope

2. 获取access_token

请求网址：https://graph.qq.com/oauth2.0/token

参数：grant_type,client_id,client_secret,code,redirect_uri

3. 获取open_id

请求网址：https://graph.qq.com/oauth2.0/me

参数：access_token

4. 获取授权的api，如用户信息

请求网址：https://graph.qq.com/user/get_user_info 获取用户信息

参数：access_token,oauth_consumer_key,openid

### 微信
#### 公众号
公众平台官网中的“开发 - 接口权限 - 网页服务 - 网页帐号 - 网页授权获取用户基本信息”的配置选项中，修改授权回调域名，若要统一用户账号，则需到开放平台绑定公众账号，即可使用UnionID 
> 1.获取Authorization Code

请求网址：https://open.weixin.qq.com/connect/oauth2/authorize

参数：appid,redirect_uri,response_type,scope,state,#wechat_redirect

> 2.获取access_token

请求网址：https://api.weixin.qq.com/sns/oauth2/access_token

参数：appid,secret,code,grant_type

> 3.获取open_id和用户信息

请求网址：https://api.weixin.qq.com/sns/userinfo

参数：access_token,openid,lang

#### 网站
条件：开放平台，创建网站
> 1.获取Authorization Code

请求网址：https://open.weixin.qq.com/connect/qrconnect

参数：appid,redirect_uri,response_type,scope,state

直接重定向到微信页面，无返回
> 2.获取access_token

请求网址：https://api.weixin.qq.com/sns/oauth2/access_token

参数：appid,secret,code,grant_type
正确返回：
{
"access_token":"ACCESS_TOKEN",
"expires_in":7200,
"refresh_token":"REFRESH_TOKEN","openid":"OPENID",
"scope":"SCOPE"
}
错误返回：
{
"errcode":40029,"errmsg":"invalid code"
}
> 3.获取open_id和用户信息

请求网址：https://api.weixin.qq.com/sns/userinfo

参数：access_token,openid,lang
#### 移动应用
条件：开放平台，创建app,得到appID和appsecret

移动应用上微信登录只提供原生的登录方式，需要用户安装微信客户端才能配合使用。

### 步骤
获取code，返回重定向地址

根据重定向地址请求access_token和用户信息。。。


抽象出来就两步
1. 验证 Authorize

2. 回调 Callback


## 重点
- 整理出步骤
- 每个步骤输入参数，输出结果，异常返回等。。
- 多种情况下，先整理出一种情况








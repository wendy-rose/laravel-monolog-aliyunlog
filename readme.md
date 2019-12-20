## 基于阿里云日志存储的monolog

这个包主要是将laravel原本的日志储存改成储存到阿里云日志，这里如果是error错误，阿里云日志的topic统一是error，其他都是自定义（即调用laravel自带的Log工具类时给的标识），这个包只适用于laravel框架，并且版本是5.5以上

### 安装

```
composer require wendy/monolog-aliyunlog
```

在`.env`中添加阿里云日志配置

```
ALIYUN_ACCESS_KEY_ID=阿里云访问密钥AccessKeyId
ALIYUN_ACCESS_KEY_SECRET=阿里云访问密钥AccessKeySecret
# https://help.aliyun.com/document_detail/29008.html
# 如杭州公网 cn-hangzhou.log.aliyuncs.com
# 如杭州内网 cn-hangzhou-intranet.log.aliyuncs.com
SLS_ENDPOINT=创建project所属区域匹配的Endpoint
SLS_PROJECT=创建的项目名称
SLS_STORE=创建的日志库名称
```

修改`config/logging.php`的`channels`

```php
'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['aliyunlog'],
            'ignore_exceptions' => false,
        ],
        'aliyunlog' => [
            'driver' => 'custom',
            'via' => AliyunLogger::class
        ],
    ],
```

### 使用

只需正常的使用laravel框架自带的Log工具类即可，同时错误信息也会同步到阿里云日志


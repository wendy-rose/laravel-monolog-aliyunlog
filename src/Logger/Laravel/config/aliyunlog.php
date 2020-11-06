<?php
/**
 * Created by PhpStorm.
 * User: Wendy
 * Date: 2020/11/6
 * Time: 15:58
 */

return [
    'access_key_id' => env('ALIYUN_ACCESS_KEY_ID', ''),
    'access_key_secret' => env('ALIYUN_ACCESS_KEY_SECRET', ''),
    'sls_endpoint' => env('SLS_ENDPOINT', ''),
    'sls_project' => env('SLS_PROJECT', ''),
    'sls_store' => env('SLS_STORE', ''),
];
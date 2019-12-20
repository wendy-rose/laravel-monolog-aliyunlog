<?php
/**
 * Created by PhpStorm.
 * User: Wendy
 * Date: 2019/12/19
 * Time: 16:16
 */

namespace Logger\Laravel\Via;


use Logger\Monolog\Handler\AliyunLogHandler;
use Monolog\Logger;

class AliyunLogger
{

    /**
     * Create a custom Monolog instance.
     *
     * @param  array $config
     * @return Logger
     * @throws \Exception
     */
    public function __invoke(array $config)
    {
        $channel = $config['name'] ?? env('APP_ENV');
        $monolog = new Logger($channel);
        $monolog->pushHandler(new AliyunLogHandler());
        return $monolog;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User: Wendy
 * Date: 2019/12/19
 * Time: 16:17
 */

namespace Logger\Monolog\Handler;


use Aliyun\SLS\Client;
use Aliyun\SLS\Models\LogItem;
use Aliyun\SLS\Requests\PutLogsRequest;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class AliyunLogHandler extends AbstractProcessingHandler
{

    protected $accessKeyId;
    protected $accessKeySecret;
    protected $endpoint;
    protected $project;
    protected $logStore;

    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        $this->accessKeyId = config('aliyunlog.access_key_id');
        $this->accessKeySecret = config('aliyunlog.access_key_secret');
        $this->endpoint = config('aliyunlog.sls_endpoint');
        $this->project = config('aliyunlog.sls_project');
        $this->logStore = config('aliyunlog.sls_store');
        parent::__construct($level, $bubble);
    }

    /**
     * 将错误和日志记录到阿里云
     * @param array $record
     * @throws \Aliyun\SLS\Exception
     */
    protected function write(array $record):void
    {
        try {
            //这里如果是error错误，阿里云日志的topic统一是error，其他都是自定义（即调用laravel自带的Log工具类时给的标识）
            if ($record['level'] == Logger::ERROR) {
                $topic = strtolower($record['level_name']);
            } else {
                $topic = $record['message'];
            }
            $data = [
                'level' => $record['level_name'],
                'method' => request()->getMethod(),
                'requestUri' => request()->getPathInfo(),
                'requestData' => $this->getRequestData(),
                'time' => date('Y-m-d H:i:s', time()),
                'topic' => $topic,
                'context' => json_encode($record, JSON_UNESCAPED_UNICODE),
                'ip' => request()->getClientIp(),
                'header' => $this->getHeader(),
                'contentType' => request()->getContentType() ?? '',
            ];
            $logs = [new LogItem($data)];
            $client = new Client($this->endpoint, $this->accessKeyId, $this->accessKeySecret);
            $putLogsRequest = new PutLogsRequest($this->project, $this->logStore, $topic, $data['ip'], $logs);
            $client->putLogs($putLogsRequest);
        } catch (\Exception $exception) {

        }
    }

    /**
     * 获取头部
     * @return false|string
     */
    protected function getHeader()
    {
        $header = request()->header() ?? [];
        return json_encode($header, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 获取请求参数
     * @return string
     */
    protected function getRequestData()
    {
        $data = ['__get__' => [], '__post__' => [], '__put__' => [], '__delete__' => []];
        $data['__get__'] = request()->query() ?? [];
        if (!request()->isMethod('GET')) {
            if (request()->isJson()) {
                $body = request()->getContent() ? json_decode(request()->getContent(), true): [];
            } else {
                $body = request()->post();
            }
            if (request()->isMethod('POST')) {
                $data['__post__'] = $body;
            } elseif (request()->isMethod('PUT')) {
                $data['__put__'] = $body;
            } elseif (request()->isMethod('DELETE')) {
                $data['__delete__'] = $body;
            }
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
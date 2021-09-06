<?php
/**
  * RpcMessage实例
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/06
  * @Description: 
*/
namespace Zhongrui\Mq\Message;

use Exception;

class RpcMessage extends Message implements RpcMessageInterface
{   

    protected $queue = '';

    protected $correlationId = null;

    protected $payload;

    protected $response;

    public function payload(): string
    {
        return $this->encodeMessage();
    }

    public function setPayload($data)
    {
        $this->payload = $data;
        return $this;
    }

    public function setQueue(string $queue)
    {
        $this->queue = $queue;
    }

    public function getQueue(): string
    {
        return $this->queue;
    }

    public function getCorrelationId(): string
    {
        $this->correlationId = uniqid();
        return $this->correlationId;
    }

    public function encodeMessage(): string
    {
        if(is_array($this->payload)){
            return json_encode($this->payload);
        }

        if(is_string($this->payload)){
            return $this->payload;
        }

        throw new Exception(' Payload value is not allow !');
    }

    public function decodeMessage(string $data)
    {
        try {
            return json_decode($data, true);
        } catch(\Exception $e){
            return $data;
        }
    }

    /**
     * 获取Rpc调用结果
     * @return mixed
    */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * 接收回调
     * @param  Response   $resp rpc回调参数
    */
    public function onResponse($resp)
    {
        if($resp->get('correlation_id') == $this->correlationId){
            $this->response = $resp->body;
        }
    }
}
?>
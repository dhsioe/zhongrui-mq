<?php
/**
  * MQ消息
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/04
  * @Description: 
*/
namespace Zhongrui\Mq\Message;

use Exception;

class Message implements MessageInterface
{

    /**
     * 消息类型
     * @var string
    */
    protected $type = '';

    /**
     * 交换机
     * @var string
    */
    protected $exchange = '';

    /**
     * 路由
     * @var string
    */
    protected $routeKey;


    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setExchange(string $exchange)
    {
        $this->exchange = $exchange;
        return $this;
    }

    public function getExchange(): string
    {
        return $this->exchange;
    }

    public function setRouteKey(string $routeKey)
    {
        $this->routeKey = $routeKey;
        return $this;
    }

    public function getRouteKey(): string
    {
        return $this->routeKey;
    }

    public function encodeMessage(): string
    {
        throw new Exception('You have to overwrite encodeMessage() method .');
    }

    public function decodeMessage(string $data)
    {
        throw new Exception('You have to overwrite decodeMessage() method .');
    }
}
?>
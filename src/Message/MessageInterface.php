<?php
/**
  * 
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/04
  * @Description: 
*/
namespace Zhongrui\Mq\Message;


interface MessageInterface
{
    /**
     * 消息类型
    */
    public function setType(string $type);

    /**
     * 获取消息类型
    */
    public function getType(): string;

    /**
     * 设置交换机
    */
    public function setExchange(string $exchange);

    /**
     * 配置交换机
    */
    public function getExchange(): string;
    
    /**
     * 设置路由Key
    */
    public function setRouteKey(string $routeKey);
    
    /**
     * 获取路由Key
    */
    public function getRouteKey(): string;

    /**
     * 封装信息
     * @return string
    */
    public function encodeMessage(): string;

    /**
     * 转换信息
    */
    public function decodeMessage(string $data);
}
?>
<?php
/**
  * 远程调用生产者接口
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/06
  * @Description: 
*/
namespace Zhongrui\Mq\Message;


interface RpcMessageInterface extends MessageInterface
{
    public function setQueue(string $queue);

    public function getQueue(): string;
    
    public function payload(): string;
    /**
     * 设置唯一标识ID
     */
    public function getCorrelationId(): string;

    /**
     * 接收队列异步回调结果
    */
    public function onResponse($resp);

    /**
     * 获取异步回调结果
    */
    public function getResponse();
}
?>
<?php
/**
  * 
  * @author: hsioe1111@gmail.com
  * @Date: 
  * @Description: 
*/
namespace Zhongrui\Mq;

use Exception;
use PhpAmqpLib\Message\AMQPMessage;
use Zhongrui\Mq\MqConnection;
use Zhongrui\Mq\Message\ProduceMessageInterface;
use Zhongrui\Mq\Message\RpcMessageInterface;

class ZhongruiMq
{
    /**
     * Mq配置
     * @var array
    */
    protected $config;

    /**
     *  MQ链接器
     *  @var MqConnection
    */
    protected $factory;

    public function __construct($config = [])
    {
        $this->setConfig($config);
        $this->factory = new MqConnection($this->config);
    }

    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     *  生产普通消息
     *  @param ProduceMessageInterface  $produce  普通消息生产者接口对象
     *  @param bool                     $confirm  是否确认ACK
     *  @param int                      $timeout  发送超时时间
    */
    public function produce(ProduceMessageInterface $produce, bool $confirm = false, $timeout = 3)
    {
        $result = false;

        $message = new AMQPMessage($produce->payload(), $produce->getProperties());

        try {
            $channel = $confirm? $this->factory->getConfirmChannel():
                                 $this->factory->getChannel();
            $channel->exchange_declare($produce->getExchange(), $produce->getType(), false, false, false);
            $channel->basic_publish($message, $produce->getExchange(), $produce->getRouteKey());
            $channel->set_ack_handler(function () use (&$result){
                 $result = true;
            });
            $channel->wait_for_pending_acks_returns($timeout); 
        } catch(\Exception $e){
            isset($channel) && $channel->close();
            throw $e;
        } finally {
            $channel->close();
        }

        return $result;
    }

    /**
     * Rpc远程调用
     * @param  RpcMessageInterface    $produce  远程调用生产者
     * @param  int                    $timeout  超时 默认3s
     * @return mixed
    */
    public function rpcProduce(RpcMessageInterface $produce, int $timeout = 1)
    {
        $channel = $this->factory->getChannel();
        $channel->queue_declare($produce->getQueue(), false, false, true, false);
        $channel->basic_consume($produce->getQueue(), 
            '', false, false, false, false, [$produce, 'onResponse']);

        $message = new AMQPMessage($produce->encodeMessage(), [
            'correlation_id' => $produce->getCorrelationId(),
            'reply_to'       => $produce->getQueue()
        ]);
        $channel->basic_publish($message, $produce->getExchange(), $produce->getRouteKey());

        while(!$produce->getResponse()){
            $channel->wait(null, false, $timeout);
        }
        
        if(empty($produce->getResponse())) {
            throw new Exception('rpc produce wait timeout!');
        }

        $channel->close();
        return $produce->decodeMessage($produce->getResponse());
    }
}
?>
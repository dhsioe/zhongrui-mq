<?php
/**
  * 
  * @author: hsioe1111@gmail.com
  * @Date: 
  * @Description: 
*/
namespace Zhongrui\Mq;

use PhpAmqpLib\Message\AMQPMessage;
use Zhongrui\Mq\MqConnection;
use Zhongrui\Mq\Message\ProduceMessageInterface;

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
            $channel->set_ack_handler(function (&$result){
                 $result=true;
            });
            $channel->wait_for_pending_acks($timeout); 
        } catch(\Exception $e){
            isset($channel) && $channel->close();
            throw $e;
        } finally {
            $channel->close();
        }

        return $result;
    }
}
?>
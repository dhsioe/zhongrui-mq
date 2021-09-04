<?php
/**
  * 
  * @author: hsioe1111@gmail.com
  * @Date: 
  * @Description: 
*/
namespace Zhongrui\Mq;

class ZhongruiMq
{
    protected $driver;


    public function __construct($driver)
    {
        $this->driver = MqDriverFactory::getDriver($driver);
    }

}

/**
 *  发送消息
 *  ZhongruiMq::instance($message)->produce();
 *  if($message->isRpc()){
 *      return $message->getResponse();
 *  }
 * 
 *  RpcMessage
 *  
 *  ZhongruiMq::instance('RpcProduce')->produce('Topic', 'tag', 'msg')
 *  ZhongruiMq::instance('Produce')->produce('Topic', 'tag', 'msg')
 *  ZhongruiMq::instance('DelayProduce')->produce('queue-name', $message)
 *  ZhongruiMq::instance('TaskProduce')->produce('queue-name', $message)
 *  
*/
?>

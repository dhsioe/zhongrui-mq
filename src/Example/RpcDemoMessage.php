<?php
/**
  * RPCDemo Message
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/06
  * @Description: 
*/
namespace Zhongrui\Mq\Example;

use Zhongrui\Mq\Message\RpcMessage;

class RpcDemoMessage extends RpcMessage
{
    const TOPIC = 'MQ_TOPIC_FILE';

    const RouteKey = 'FILE_CONSUMER.GETUSERINFO';

    const TYPE = 'topic';

    public function __construct($message)
    {
        $this->setExchange(self::TOPIC);
        $this->setRouteKey(self::RouteKey);
        $this->setType(self::TYPE);
        $this->setQueue('reply_userinfo');
        $this->setPayload($message);
    }
}
?>
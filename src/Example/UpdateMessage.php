<?php
namespace Zhongrui\Mq\Example;

use Zhongrui\Mq\Message\ProduceMessage;

class UpdateMessage extends ProduceMessage
{
    const TOPIC = 'topic_logs';

    const RouteKey = 'p';

    const TYPE = 'topic';

    public function __construct($message = '')
    {
        $this->setExchange(self::TOPIC);
        $this->setRouteKey(self::RouteKey);
        $this->setType(self::TYPE);
        $this->setPayload($message);
    }

}
?>
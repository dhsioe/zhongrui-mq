<?php
/**
  * 普通生产者 | Produce消息
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/04
  * @Description: 
*/
namespace Zhongrui\Mq\Message;

use Exception;

class ProduceMessage extends Message implements ProduceMessageInterface
{
    /**
     * 消息载体
     * @var string
    */
    protected $payload;

    /**
     * @var array
    */
    protected $properties 
        = [
            'content_type'  => 'text/plain',
            'delivery_mode' => 2
        ];

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function setPayload($data)
    {
        $this->payload = $data;
        return $this;
    }

    public function payload(): string
    {
        return $this->payload;
    }

    public function serialize(): string
    {
        if(is_string($this->payload)){
            return $this->payload;
        }

        if(is_array($this->payload)){
            return json_encode($this->payload, JSON_UNESCAPED_UNICODE);
        }

        throw new Exception(' Payload value is not allow pass!');
    }
}
?>
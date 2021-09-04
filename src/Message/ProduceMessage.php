<?php
/**
  * 普通生产者 | Produce消息
  * @author: hsioe1111@gmail.com
  * @Date: 2021/09/04
  * @Description: 
*/
namespace Zhongrui\Mq\Message;


class ProduceMessage extends Message
{
    /**
     * @var array
    */
    protected $properties 
        = [
            'content_type'  => 'text/plain',
            'delivery_mode' => 2
        ];

    public function setMessage($message)
    {
        
    }

    public function getProperies(): array
    {
        return $this->properties;
    }
}
?>
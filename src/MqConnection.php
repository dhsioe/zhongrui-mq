<?php
/**
  * MQ连接器
  * @author: hsioe1111@gmail.com
  * @Date: 
  * @Description: 
*/
namespace Zhongrui\Mq;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class MqConnection
{
    /**
     *  连接配置
     *  @var array
    */
    protected $config = [];

    /**
     *  @var AMQPStreamConnection
    */
    protected $connection;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->connection = $this->initConnection();
    }

    /**
     *  初始化Mq连接
     *  @return AMQPStreamConnection
    */
    public function initConnection(): AMQPStreamConnection
    {
        return new AMQPStreamConnection(
            $this->config['host'], $this->config['port'], $this->config['username'], $this->config['password']
        );
    }

    /**
     * 获取Channel
     * @return AMQPChannel
    */
    public function getChannel($channelId = null): AMQPChannel
    {
        return $this->connection->channel($channelId);
    }

    /**
     * 获取ACKChannel
     * @return AMQPChannel
    */
    public function getConfirmChannel(): AMQPChannel
    {
        $id = uniqid();
        
        $channel = $this->getChannel($id);

        $channel->confirm_select();
        return $channel;
    }

    /**
     *  关闭链接
    */
    public function close()
    {
        $this->connection->close();
    }
}
?>
# 简介

`zhongrui-mq` 是基于 `AMQP` 封装的 工具包

主要为了解决项目中 引入 `php-amqplib/php-amqplib` 包后仍需编写一些额外的代码来支持生产消息



# 使用Composer安装 `zhongrui-mq`

```shell
composer require "zhongrui-mq" ^1.0
```



# 使用示例

### 构建一个普通生产者

```php
<?php
// ProduceDemo

use Zhongrui\Mq\Message\ProduceMessage;

class ProduceDemoMessage extends ProduceMessage
{
    public function __construct(string $message)
    {   
        // 设置你的交换机
        $this->setExchange('You-Exchange');
        // 设置你的路由
        $this->setRouteKey('You-RouteKey');
        // 设置消息类型
        $this->setType('topic');
        // 将消息加载到payload
        $this->setPayload($message);
    }
}
?>
```

以上就已经完成了一个消息生产者的配置， 接下来我们展示如何使用它

在你业务中需要发送MQ的地方, 编写如下代码

```php
<?php
 namespace YouProject\Demo;
 
 use Zhongrui\Mq\ZhongruiMq;
 use YouProject\Message\ProduceDemoMessage;

 public function login()
 {
     // Login Success
     // Config::get('amqp') 按需加载你项目中的AMQP配置即可
     $mq = ZhongruiMq(Config::get('amqp'));
     $message = ProduceDemoMessage([
         'id'  =>  '1',
         'username' => 'demo'
     ]);
     $mq->produce($message);
 }
?>
```



### 构建一个RPC远程调用生产者

有时我们需要接收`消费者`的返回数据, 我们就可以用`Rpc`的方式来构建生产者

先构建一个 `RpcDemoProduce.php`

```php
<?php
 namespace YouProject\Demo;

 use Zhongrui\Mq\Message\RpcMessage;
 
 class RpcDemoMessage extends RpcMessage
 {
      public function __construct($message)
      {
          $this->setExchange('You-Exchange');
          $this->setRouteKey('You-RouteKey');
          $this->setType('topic');
          // RPC消息必须设置接收远程调用结果的队列
          $this->setQueue('rpc_queue');
          $this->setPayload($message);
      }
 }
?>
```

在你业务中需要发送 MQ 的地方加上如下代码

```php
<?php
 namespace YouProject\Demo;
 
 use Zhongrui\Mq\ZhongruiMq;
 use YouProject\Demo\RpcDemoProduce;

 public function getUserInfo($userId)
 {
      $mq = new ZhongruiMq(Config::get('amqp'));
      $message = new RpcDemoProduce([
          "id" => 2 
      ]);
     
      $userInfo = $mq->produceRpc($message, 3);
      return $userInfo;
 }
?>
```



# 单元测试

目录 `tests`  运行 `.\vendor\bin\phpunit ./tests --group ZhongruiMq`

```php
PHPUnit 9.5.9 by Sebastian Bergmann and contributors.

Warning:       Your XML configuration validates against a deprecated schema.
Suggestion:    Migrate your XML configuration using "--migrate-configuration"!

Warning:       Test case class not matching filename is deprecated
               in C:\Users\Administrator\Desktop\david\php\zhongrui-mq\tests\ZhongruiMqTest.php
               Class name was 'ZhongRuiMqTest', expected 'ZhongruiMqTest'

..                                                                  2 / 2 (100%)

Time: 00:00.065, Memory: 6.00 MB

OK (2 tests, 2 assertions)
PS C:\Users\Administrator\Desktop\david\php\zhongrui-mq> 
```



# 感谢

本代码中借鉴了 `hyperf\amqp`  组件的实现方式

[hyper](https://github.com/hyperf/hyperf)
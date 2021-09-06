<?php
/**
  * 支付类测试
  * @author: hsioe1111@gmail.com
  * @Date: 
  * @Description: 
*/
namespace Zhongrui\Mq\Tests;

use Zhongrui\Mq\Example\UpdateMessage;
use Zhongrui\Mq\ZhongruiMq;

class ZhongRuiMqTest extends \PHPUnit\Framework\TestCase
{
    protected $config = [
        'host' => '192.168.1.232',
        'port' => 5672,
        'username' => 'ttss',
        'password' => 'ttss'
    ];

    /**
     * 普通生产者
     * @group ZhongruiMq
    */
    public function testProduce()
    {
        $mq = new ZhongruiMq($this->config);
        $message = new UpdateMessage("1");
        $rv = $mq->produce($message, true);
        $this->assertTrue($rv);
    }
}
?>
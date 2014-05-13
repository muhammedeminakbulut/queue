<?php

namespace TreeHouse\Queue\Tests\Message\Publisher;

use TreeHouse\Queue\Message\Composer\DefaultMessageComposer;
use TreeHouse\Queue\Message\Composer\MessageComposerInterface;
use TreeHouse\Queue\Message\Message;
use TreeHouse\Queue\Message\MessageProperties;
use TreeHouse\Queue\Message\Publisher\AmqpMessagePublisher;
use TreeHouse\Queue\Message\Publisher\MessagePublisherInterface;
use TreeHouse\Queue\Message\Serializer\PhpSerializer;

class AmqpMessagePublisherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \AMQPExchange|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $exchange;

    /**
     * @var MessageComposerInterface
     */
    protected $composer;

    /**
     * @var MessagePublisherInterface
     */
    protected $publisher;

    public function testConstructor()
    {
        $this->assertInstanceOf(AmqpMessagePublisher::class, $this->publisher);
    }

    public function testCreateMessage()
    {
        $body    = 'test';
        $message = $this->publisher->createMessage($body);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($body, $message->getBody());
        $this->assertEquals(MessageProperties::CONTENT_TYPE_TEXT_PLAIN, $message->getProperties()->getContentType());
        $this->assertEquals(MessageProperties::DELIVERY_MODE_PERSISTENT, $message->getProperties()->getDeliveryMode());
    }

    public function testPublish()
    {
        $body    = 'test';
        $message = $this->publisher->createMessage($body);

        $this->exchange
            ->expects($this->once())
            ->method('publish')
            ->with($body, null, AMQP_NOPARAM)
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->publisher->publish($message));
    }

    public function testPublishArguments()
    {
        $body    = 'test';
        $route   = 'foo_route';
        $message = $this->publisher->createMessage($body);
        $message->setRoutingKey($route);

        $this->exchange
            ->expects($this->once())
            ->method('publish')
            ->with($body, $route, AMQP_IMMEDIATE)
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->publisher->publish($message, true));
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage You cannot publish a message in the past
     */
    public function testPublishInThePast()
    {
        $message = $this->publisher->createMessage('test');

        $this->exchange
            ->expects($this->never())
            ->method('publish')
        ;

        $this->publisher->publish($message, null, new \DateTime('-10 minutes'));
    }

    /**
     * @expectedException        \LogicException
     * @expectedExceptionMessage You cannot set a publish date for a high priority message
     */
    public function testScheduleHighPriority()
    {
        $message = $this->publisher->createMessage('test');

        $this->exchange
            ->expects($this->never())
            ->method('publish')
        ;

        $this->publisher->publish($message, true, new \DateTime('+10 minutes'));
    }

    public function testPublishDelayed()
    {
        $this->markTestSkipped('Find out a way to mock the deferred queue first');
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->exchange = $this
            ->getMockBuilder(\AmqpExchange::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->composer = new DefaultMessageComposer(new PhpSerializer());
        $this->publisher = new AmqpMessagePublisher($this->exchange, $this->composer);
    }
}

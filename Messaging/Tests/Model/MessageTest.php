<?php

namespace Miliooo\Messaging\Tests\Model;

use Miliooo\Messaging\Model\Message;

/**
 * Description of Message
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * 
     * @var Message
     */
    private $message;

    public function setUp()
    {
        $this->message = $this->getMockForAbstractClass('Miliooo\Messaging\Model\Message');
    }

    public function testCreatedAtWorks()
    {
        $date = new \DateTime('2012-10-03');
        $this->message->setCreatedAt($date);
        $this->assertSame($date, $this->message->getCreatedAt());
    }

    public function testBodyWorks()
    {
        $body = 'the body';
        $this->message->setBody($body);
        $this->assertSame($body, $this->message->getBody());
    }

}

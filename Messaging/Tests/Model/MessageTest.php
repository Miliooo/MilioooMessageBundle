<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Model;

use Miliooo\Messaging\Model\Message;

/**
 * Test file for the message model
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

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Model\MessageInterface', $this->message);
    }

    public function testGetIdDefaultsToNull()
    {
        $this->assertNull($this->message->getId());
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

    public function testSenderWorks()
    {
        $sender = $this->getMock('Miliooo\Messaging\Model\ParticipantInterface');
        $this->message->setSender($sender);
        $this->assertSame($sender, $this->message->getSender());
    }

    public function testAddMessageMetaWorks()
    {
        $messageMetaMock = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->message->addMessageMeta($messageMetaMock);
        $this->assertTrue($this->message->getMessageMeta()->contains($messageMetaMock));
    }
}

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
use Miliooo\Messaging\Tests\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Model\MessageMeta;

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

    public function testAddMessageMetaAddsItToTheArrayCollection()
    {
        $messageMetaMock = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->message->addMessageMeta($messageMetaMock);
        $this->assertTrue($this->message->getMessageMeta()->contains($messageMetaMock));
    }

    public function testAddMessageMetaSetsTheCurrentMessage()
    {
        $messageMetaMock = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $messageMetaMock->expects($this->once())->method('setMessage')->with($this->message);
        $this->message->addMessageMeta($messageMetaMock);
    }

    public function testGetMessageMetaForParticipant()
    {
        $participant1 = new ParticipantTestHelper(1);
        $participant2 = new ParticipantTestHelper(2);

        $messageMetaMock1 = $this->getNewMessageMeta();
        $messageMetaMock1->setParticipant($participant1);

        $this->message->addMessageMeta($messageMetaMock1);
        $messageMetaMock2 = $this->getNewMessageMeta();
        $messageMetaMock2->setParticipant($participant2);
        $this->message->addMessageMeta($messageMetaMock2);
        $this->assertEquals($messageMetaMock2, $this->message->getMessageMetaForParticipant($participant2));
    }

    public function testGetMessageMetaForParticipantReturnsNullWhenNotFound()
    {
        $participant = new ParticipantTestHelper(100);
        $messageMeta = $this->getNewMessageMeta();
        $messageMeta->setParticipant($participant);

        $this->message->addMessageMeta($messageMeta);

        $participant20 = new ParticipantTestHelper(20);
        $this->assertNull($this->message->getMessageMetaForParticipant($participant20));
    }

    public function testThreadWorks()
    {
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->message->setThread($thread);
        $this->assertSame($thread, $this->message->getThread());
    }

    /**
     * Helper function
     * 
     * @return MessageMeta
     */
    protected function getNewMessageMeta()
    {
        return $this->getMockForAbstractClass('Miliooo\Messaging\Model\MessageMeta');
    }
}

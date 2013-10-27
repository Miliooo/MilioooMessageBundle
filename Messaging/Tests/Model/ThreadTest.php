<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Model;

use Miliooo\Messaging\Model\Thread;
use Miliooo\Messaging\Model\Message;

/**
 * Test file for the thread model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var Thread
     */
    protected $thread;

    public function setUp()
    {
        $this->thread = $this->getMockForAbstractClass('Miliooo\Messaging\Model\Thread');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $this->thread);
    }

    public function testIdWorks()
    {
        $this->assertAttributeEquals(null, 'id', $this->thread);
        $this->assertNull($this->thread->getId());
    }

    public function testSubjectWorks()
    {
        $subject = 'this is the subject';
        $this->thread->setSubject($subject);
        $this->assertSame($subject, $this->thread->getSubject());
    }

    public function testCreatedByWorks()
    {
        $participant = $this->getMock('Miliooo\Messaging\Model\ParticipantInterface');
        $this->thread->setCreatedBy($participant);
        $this->assertEquals($participant, $this->thread->getCreatedBy());
    }

    public function testCreatedAtWorks()
    {
        $createdAt = new \DateTime('2013-10-12 00:00:00');
        $this->thread->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $this->thread->getCreatedAt());
    }

    public function testMessagesIsAnArrayCollection()
    {
        $this->assertAttributeInstanceOf('Doctrine\Common\Collections\ArrayCollection', 'messages', $this->thread);
    }

    public function testAddMessageWorksByCheckingItIsPartOfTheGetMessages()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->thread->addMessage($message);
        $messages = $this->thread->getMessages();
        $this->assertTrue($messages->contains($message));
    }

    public function testAddMessageSetsTheMessageThreadToCurrentThread()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $message->expects($this->once())->method('setThread')->with($this->thread);
        $this->thread->addMessage($message);
    }

    public function testGetMessagesReturnsRightAmountOfmessages()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->thread->addMessage($message);
        $this->thread->addMessage($message);
        $this->assertCount(2, $this->thread->getMessages());
        $this->thread->addMessage($message);
        $this->assertCount(3, $this->thread->getMessages());
    }

    public function testGetFirstMessage()
    {
        $message = $this->getNewMessage();
        $message->setBody('the first message');
        $message2 = $this->getNewMessage();
        $message2->setBody('this is the second message');
        $this->thread->addMessage($message);
        $this->thread->addMessage($message2);

        $this->assertCount(2, $this->thread->getMessages());
        $this->assertEquals('the first message', $this->thread->getFirstMessage()->getBody());
    }

    public function testGetLastMessage()
    {
        $message = $this->getNewMessage();
        $message->setBody('the first message');
        $message2 = $this->getNewMessage();
        $message2->setBody('this is the second message');
        $this->thread->addMessage($message);
        $this->thread->addMessage($message2);

        $this->assertCount(2, $this->thread->getMessages());
        $this->assertEquals('this is the second message', $this->thread->getLastMessage()->getBody());
    }

    /**
     * Helper function to get a new message instance from an abstract class
     * 
     * @return Message
     */
    protected function getNewMessage()
    {
        return $this->getMockForAbstractClass('Miliooo\Messaging\Model\Message');
    }
}

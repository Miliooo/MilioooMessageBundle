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
use Miliooo\Messaging\Tests\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Model\ThreadMeta;

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
        $participant = new ParticipantTestHelper('participant');
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
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->thread->getMessages());
    }

    public function testThreadMetaIsAnArrayCollection()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->thread->getThreadMeta());
    }

    public function testParticipantsIsAnArrayCollection()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->thread->getThreadMeta());
    }

    public function testAddMessageWorksByCheckingItIsPartOfTheGetMessages()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->thread->addMessage($message);
        $messages = $this->thread->getMessages();
        $this->assertTrue($messages->contains($message));
    }

    public function testAddThreadMetaWorksByCheckingItIsPartOfTheGetThreadMeta()
    {
        $threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
        $this->thread->addThreadMeta($threadMeta);
        $threadMetas = $this->thread->getThreadMeta();
        $this->assertTrue($threadMetas->contains($threadMeta));
    }

    public function testAddMessageSetsTheMessageThreadToCurrentThread()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $message->expects($this->once())->method('setThread')->with($this->thread);
        $this->thread->addMessage($message);
    }

    public function testAddThreadMetaSetsTheThreadMetaToTheCurrentThread()
    {
        $threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
        $threadMeta->expects($this->once())->method('setThread')->with($this->thread);
        $this->thread->addThreadMeta($threadMeta);
    }

    public function testGetMessagesReturnsRightAmountOfmessages()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->assertCount(0, $this->thread->getMessages());
        $this->thread->addMessage($message);
        $this->thread->addMessage($message);
        $this->assertCount(2, $this->thread->getMessages());
    }

    public function testGetThreadMetaReturnsRightAmountOfMetas()
    {
        $threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
        $this->assertCount(0, $this->thread->getThreadMeta());
        $this->thread->addThreadMeta($threadMeta);
        $this->thread->addThreadMeta($threadMeta);
        $this->assertCount(2, $this->thread->getThreadMeta());
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

    public function testGetThreadMetaForParticipant()
    {
        $participant1 = new ParticipantTestHelper(1);
        $threadMeta = $this->getNewThreadMeta();
        $threadMeta->setParticipant($participant1);
        $this->thread->addThreadMeta($threadMeta);

        $participant2 = new ParticipantTestHelper(2);
        $threadMeta2 = $this->getNewThreadMeta();
        $threadMeta2->setParticipant($participant2);
        $this->thread->addThreadMeta($threadMeta2);

        $this->assertSame($threadMeta2, $this->thread->getThreadMetaForParticipant($participant2));
    }

    public function testGetThreadMetaForParticipantReturnsNullWhenNotFound()
    {
        $participant1 = new ParticipantTestHelper(1);

        $this->assertNull($this->thread->getThreadMetaForParticipant($participant1));
        $threadMeta = $this->getNewThreadMeta();
        $threadMeta->setParticipant($participant1);
        $this->thread->addThreadMeta($threadMeta);

        $participant2 = new ParticipantTestHelper(2);
        $this->assertNull($this->thread->getThreadMetaForParticipant($participant2));
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

    /**
     * Helper function to get a new thread meta from an abstract class
     *
     * @return ThreadMeta
     */
    protected function getNewThreadMeta()
    {
        return $this->getMockForAbstractClass('Miliooo\Messaging\Model\ThreadMeta');
    }
}

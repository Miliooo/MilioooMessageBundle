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
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Model\ThreadMeta;
use Miliooo\Messaging\TestHelpers\Model\Message;

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

    public function testGettersAndSetters()
    {
        $this->subjectWorks();
        $this->createdByWorks();
        $this->createdAtworks();
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

    public function testArrayCollections()
    {
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->thread->getMessages());
        $this->assertInstanceOf('Doctrine\Common\Collections\ArrayCollection', $this->thread->getThreadMeta());
        $this->assertAttributeInstanceOf('Doctrine\Common\Collections\ArrayCollection', 'participants', $this->thread);
    }

    public function testAddMessageWorksByCheckingItIsPartOfTheGetMessages()
    {
        $message = new Message();
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

    public function testGetParticipants()
    {
        $participant = new ParticipantTestHelper(1);
        $threadMeta = $this->getNewThreadMeta();
        $threadMeta->setParticipant($participant);
        $threadMeta->setThread($this->thread);
        $this->assertEmpty($this->thread->getParticipants());
        $this->thread->addThreadMeta($threadMeta);
        $this->assertCount(1, $this->thread->getParticipants());
        $this->assertEquals($participant, $this->thread->getParticipants()[0]);
    }

    public function testIsParticipantWithNotParticipantReturnsFalse()
    {
        //add some dummie participants first
        $participant = new ParticipantTestHelper(1);
        $threadMeta = $this->getNewThreadMeta();
        $threadMeta->setParticipant($participant);
        $this->thread->addThreadMeta($threadMeta);

        $participant2 = new ParticipantTestHelper(2);
        $threadMeta2 = $this->getNewThreadMeta();
        $threadMeta2->setParticipant($participant2);
        $this->thread->addThreadMeta($threadMeta2);

        $participant3 = new ParticipantTestHelper(3);
        $this->assertCount(2, $this->thread->getParticipants());

        $this->assertFalse($this->thread->isParticipant($participant3));
    }

    public function testGetOtherParticipants()
    {
        //add some dummie participants first
        $participant = new ParticipantTestHelper(1);
        $threadMeta = $this->getNewThreadMeta();
        $threadMeta->setParticipant($participant);
        $this->thread->addThreadMeta($threadMeta);

        $participant2 = new ParticipantTestHelper(2);
        $threadMeta2 = $this->getNewThreadMeta();
        $threadMeta2->setParticipant($participant2);
        $this->thread->addThreadMeta($threadMeta2);

        $participant3 = new ParticipantTestHelper(3);
        $threadMeta3 = $this->getNewThreadMeta();
        $threadMeta3->setParticipant($participant3);
        $this->thread->addThreadMeta($threadMeta3);

        $otherParticipants = $this->thread->getOtherParticipants($participant3);
        foreach ($otherParticipants as $otherParticipant) {
            $this->assertNotEquals($otherParticipant, $participant3);
            $this->assertContains($otherParticipant, array($participant2, $participant));
        }
    }

    public function testIsParticipantWithParticipantReturnsTrue()
    {
        $participant = new ParticipantTestHelper(1);
        $threadMeta = $this->getNewThreadMeta();
        $threadMeta->setParticipant($participant);
        $this->thread->addThreadMeta($threadMeta);
        $this->assertTrue($this->thread->isParticipant($participant));
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

    public function testLastMessageWorks()
    {
        //assert last message is null by default
        $this->assertNull($this->thread->getLastMessage());

        $message = new Message();
        $message->setBody('hoi hoi');

        $this->thread->setLastMessage($message);
        $this->assertSame($message, $this->thread->getLastMessage());
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

    protected function subjectWorks()
    {
        $subject = 'this is the subject';
        $this->thread->setSubject($subject);
        $this->assertSame($subject, $this->thread->getSubject());
    }

    protected function createdByWorks()
    {
        $participant = new ParticipantTestHelper('participant');
        $this->thread->setCreatedBy($participant);
        $this->assertEquals($participant, $this->thread->getCreatedBy());
    }

    protected function createdAtWorks()
    {
        $createdAt = new \DateTime('2013-10-12 00:00:00');
        $this->thread->setCreatedAt($createdAt);
        $this->assertSame($createdAt, $this->thread->getCreatedAt());
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

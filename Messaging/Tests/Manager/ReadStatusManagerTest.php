<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\ReadStatusManager;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\TestHelpers\Model\Message;
use Miliooo\Messaging\TestHelpers\Model\MessageMeta;
use Miliooo\Messaging\TestHelpers\Model\Thread;
use Miliooo\Messaging\TestHelpers\Model\ThreadMeta;
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;

/**
 * Test file for the read status manager
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ReadStatusManager
     */
    private $readStatusManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $messageRepository;

    /**
     * @var ParticipantInterface
     */
    private $participant;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $message;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $messageMeta;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadMeta;

    public function setUp()
    {
        $this->participant = new ParticipantTestHelper(1);
        $this->messageRepository = $this->getMock('Miliooo\Messaging\Repository\MessageRepositoryInterface');
        $this->readStatusManager = new ReadStatusManager($this->messageRepository);
        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\ReadStatusManagerInterface', $this->readStatusManager);
    }

    public function testUpdateReadStatusDoesNotUpdateWhenNoMessageMetaFoundForParticipant()
    {
        $notParticipant = new ParticipantTestHelper('now participant');
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');

        $message->expects($this->once())
            ->method('getMessageMetaForParticipant')
            ->with($notParticipant)
            ->will($this->returnValue(null));


        $this->messageRepository->expects($this->never())
            ->method('save');

        $this->messageRepository->expects($this->never())
            ->method('flush');

        $this->readStatusManager->updateReadStatusForMessageCollection(
            new ReadStatus(MessageMetaInterface::READ_STATUS_READ),
            $notParticipant,
            [$message]
        );
    }

    public function testUpdateReadStatusSavesTheNewStatusWhenStatusChanged()
    {
        $this->expectsMessageMetaForParticipant();
        $this->expectsAskingMessageMetaForReadStatusWillReturn(MessageMetaInterface::READ_STATUS_READ);
        $this->expectsUpdatingMessageMetaReadStatusWith(MessageMetaInterface::READ_STATUS_NEVER_READ);
        $this->expectsThreadUnreadCountUpdateIncrement();


        //expects save
        $this->messageRepository->expects($this->once())
            ->method('save')
            ->with($this->message, false);

        //expects flush
        $this->messageRepository->expects($this->once())
            ->method('flush');

        $results = $this->readStatusManager->updateReadStatusForMessageCollection(
            new ReadStatus(MessageMetaInterface::READ_STATUS_NEVER_READ),
            $this->participant,
            [$this->message]
        );
        //expect an array wit this message
        $this->assertEquals([$this->message], $results);
        //expect only one message in the array
        $this->assertCount(1, $results);
    }

    public function testUpdateReadStatusDoesNotUpdateAndReturnsEmptyWhenNoUpdates()
    {
        $this->expectsMessageMetaForParticipant();
        $this->expectsAskingMessageMetaForReadStatusWillReturn(MessageMetaInterface::READ_STATUS_NEVER_READ);
        $this->messageMeta->expects($this->never())->method('setReadStatus');

        $this->messageRepository->expects($this->never())->method('save');
        $this->messageRepository->expects($this->never())->method('flush');

        $results = $this->readStatusManager->updateReadStatusForMessageCollection(
            new ReadStatus(MessageMetaInterface::READ_STATUS_NEVER_READ),
            $this->participant,
            [$this->message]
        );

        $this->assertCount(0, $results);
        $this->assertNotContains($this->message, $results);
    }

    public function testUpdateReadStatusWith2MessagesReturns2MessagesAndFlushesOnce()
    {
        //use a real message in this test this is more a functional test now.
        $message = $this->getMessageWithUnreadMessageMetaForParticipant();
        $message2 = $this->getMessageWithUnreadMessageMetaForParticipant();

        $this->messageRepository->expects($this->at(0))
            ->method('save')
            ->with($message, false);

        $this->messageRepository->expects($this->at(1))
            ->method('save')
            ->with($message, false);

        $this->messageRepository->expects($this->once())
            ->method('flush');

        //expects an array with both messages to be updated
        $updated = $this->readStatusManager->updateReadStatusForMessageCollection(
            new ReadStatus(MessageMetaInterface::READ_STATUS_READ),
            $this->participant,
            [$message, $message2]
        );

        $this->assertCount(2, $updated);
        $this->assertContains($message, $updated);
        $this->assertContains($message2, $updated);

        //lets check if the count has actually increased
        $count = $message->getThread()->getThreadMetaForParticipant($this->participant)->getUnreadMessageCount();
        $this->assertEquals(4, $count);
    }

    /**
     * Returns a message with status unread for the given participant;
     *
     * @return MessageInterface
     */
    protected function getMessageWithUnreadMessageMetaForParticipant()
    {
        $message = new Message();
        $messageMeta = new MessageMeta();
        $thread = new Thread();


        $threadMeta = new ThreadMeta();
        $threadMeta->setParticipant($this->participant);
        $threadMeta->setThread($thread);
        $threadMeta->setUnreadMessageCount(5);
        $thread->addThreadMeta($threadMeta);


        $messageMeta->setParticipant($this->participant);
        $messageMeta->setReadStatus(new ReadStatus(MessageMetaInterface::READ_STATUS_NEVER_READ));
        $messageMeta->setMessage($message);

        $message->addMessageMeta($messageMeta);
        $message->setThread($thread);

        $thread->addMessage($message);

        return $message;
    }

    protected function expectsMessageMetaForParticipant()
    {
        $this->message->expects($this->once())->method('getMessageMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($this->messageMeta));
    }

    /**
     * @param integer $readStatusValue The read status value
     */
    protected function expectsAskingMessageMetaForReadStatusWillReturn($readStatusValue)
    {
        $this->messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue($readStatusValue));
    }

    /**
     * @param integer $readStatusValue The read status value
     */
    protected function expectsUpdatingMessageMetaReadStatusWith($readStatusValue)
    {
        $this->messageMeta->expects($this->once())->method('setReadStatus')
            ->with(new ReadStatus($readStatusValue));

    }

    protected function expectsThreadUnreadCountUpdateIncrement()
    {
        $this->message->expects($this->once())->method('getThread')->will($this->returnValue($this->thread));

        $this->thread->expects($this->once())->method('getThreadMetaForParticipant')->with($this->participant)
            ->will($this->returnValue($this->threadMeta));

        $this->threadMeta->expects($this->once())->method('getUnreadMessageCount')->will($this->returnValue(0));

        $this->threadMeta->expects($this->once())->method('setUnreadMessageCount')->with(1);
    }
}

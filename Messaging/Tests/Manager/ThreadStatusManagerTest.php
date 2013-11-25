<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\ThreadStatusManager;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\ValueObjects\ThreadStatus;
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Test file for the thread status manager.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadStatusManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test.
     *
     * @var ThreadStatusManager
     */
    private $threadStatusManager;

    /**
     * @var ParticipantInterface
     */
    private $participant;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadMeta;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $messageRepository;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $readStatusManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $message;

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->messageRepository = $this->getMock('Miliooo\Messaging\Repository\MessageRepositoryInterface');
        $this->readStatusManager = $this->getMock('Miliooo\Messaging\Manager\ReadStatusManagerInterface');
        $this->threadStatusManager = new ThreadStatusManager(
            $this->threadRepository,
            $this->messageRepository,
            $this->readStatusManager
        );
        $this->participant = new ParticipantTestHelper(1);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\ThreadStatusManagerInterface', $this->threadStatusManager);
    }

    public function testArchivingAnActiveThreadWhichHasUnreadMessages()
    {
        $this->expectsThreadMetaForParticipant();
        $this->expectsCurrentStatusCallOnThreadMetaAndReturns(ThreadMetaInterface::STATUS_ACTIVE);

        $this->expectsSetStatusWith(ThreadMetaInterface::STATUS_ARCHIVED);

        $this->expectsMessageRepositoryCallAndWillReturn([$this->message]);

        $readStatus = new ReadStatus(MessageMetaInterface::READ_STATUS_READ);
        $this->readStatusManager->expects($this->once())
            ->method('updateReadStatusForMessageCollection')
            ->with($readStatus, $this->participant, [$this->message]);

        $result = $this->threadStatusManager->updateThreadStatusForParticipant(
            new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED),
            $this->thread,
            $this->participant
        );

        $this->assertTrue($result);
    }

    public function testArchivingAnActiveThreadWhichHasNoUnreadMessages()
    {
        $this->expectsThreadMetaForParticipant();
        $this->expectsCurrentStatusCallOnThreadMetaAndReturns(ThreadMetaInterface::STATUS_ACTIVE);
        $this->expectsSetStatusWith(ThreadMetaInterface::STATUS_ARCHIVED);
        $this->expectsMessageRepositoryCallAndWillReturn([]);
        $this->readStatusManager->expects($this->never())
            ->method('updateReadStatusForMessageCollection');


        $result = $this->threadStatusManager->updateThreadStatusForParticipant(
            new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED),
            $this->thread,
            $this->participant
        );
        $this->assertTrue($result);
    }

    public function testUpdatingAThreadStatusWithTheSameStatus()
    {
        $this->expectsThreadMetaForParticipant();
        $this->expectsCurrentStatusCallOnThreadMetaAndReturns(ThreadMetaInterface::STATUS_ARCHIVED);
        $this->expectsNoSetStatusCall();

        $result = $this->threadStatusManager->updateThreadStatusForParticipant(
            new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED),
            $this->thread,
            $this->participant
        );

        $this->assertFalse($result);
    }

    public function testMakingAnArchivedTreadActiveDoesNotCheckReadStatusUpdates()
    {
        $this->expectsThreadMetaForParticipant();
        $this->expectsCurrentStatusCallOnThreadMetaAndReturns(ThreadMetaInterface::STATUS_ARCHIVED);
        $this->expectsSetStatusWith(ThreadMetaInterface::STATUS_ACTIVE);
        $this->expectsNoMessageReadStatusUpdateChecks();

        $result = $this->threadStatusManager->updateThreadStatusForParticipant(
            new ThreadStatus(ThreadMetaInterface::STATUS_ACTIVE),
            $this->thread,
            $this->participant
        );

        $this->assertTrue($result);
    }

    /**
     * @param mixed $mixed The return value
     */
    protected function expectsMessageRepositoryCallAndWillReturn($mixed)
    {
        $this->messageRepository->expects($this->once())->method('getUnreadMessagesFromThreadForParticipant')
            ->with($this->participant, $this->thread)
            ->will($this->returnValue($mixed));
    }

    protected function expectsThreadMetaForParticipant()
    {
        $this->thread->expects($this->once())->method('getThreadMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($this->threadMeta));
    }


    /**
     * @param integer $threadStatus The current thread status
     */
    protected function expectsCurrentStatusCallOnThreadMetaAndReturns($threadStatus)
    {
        $this->threadMeta->expects($this->once())->method('getStatus')
            ->will($this->returnValue($threadStatus));
    }

    protected function expectsNoSetStatusCall()
    {
        $this->threadMeta->expects($this->never())->method('setStatus');
        $this->expectsNoMessageReadStatusUpdateChecks();

        $this->threadRepository->expects($this->never())->method('save');
    }


    /**
     * expects a set status update.
     *
     * - setting the status
     * - saving the new thread
     *
     * @param integer $newThreadStatus The new thread status
     */
    protected function expectsSetStatusWith($newThreadStatus)
    {
        $this->threadMeta->expects($this->once())->method('setStatus')
            ->with($newThreadStatus);

        $this->threadRepository->expects($this->once())->method('save')->with($this->thread);
    }

    protected function expectsNoMessageReadStatusUpdateChecks()
    {
        $this->messageRepository->expects($this->never())->method('getUnreadMessagesFromThreadForParticipant');
        $this->readStatusManager->expects($this->never())
            ->method('updateReadStatusForMessageCollection');
    }
}

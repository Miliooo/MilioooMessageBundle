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

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->threadStatusManager = new ThreadStatusManager($this->threadRepository);
        $this->participant = new ParticipantTestHelper(1);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\ThreadStatusManagerInterface', $this->threadStatusManager);
    }

    public function testUpdateThreadStatusDoesAnUpdate()
    {
        $this->thread->expects($this->once())->method('getThreadMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($this->threadMeta));

        $this->threadMeta->expects($this->once())->method('getStatus')
            ->will($this->returnValue(ThreadMetaInterface::STATUS_ACTIVE));

        $this->threadMeta->expects($this->once())->method('setStatus')
            ->with(ThreadMetaInterface::STATUS_ARCHIVED);

        $this->threadStatusManager->updateThreadStatusForParticipant(
            new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED),
            $this->thread,
            $this->participant
        );
    }

    public function testUpdateThreadStatusDoesNotUpdateWhenSameStatus()
    {
        $this->thread->expects($this->once())->method('getThreadMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($this->threadMeta));

        $this->threadMeta->expects($this->once())->method('getStatus')
            ->will($this->returnValue(ThreadMetaInterface::STATUS_ARCHIVED));

        $this->threadMeta->expects($this->never())->method('setStatus');

        $this->threadStatusManager->updateThreadStatusForParticipant(
            new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED),
            $this->thread,
            $this->participant
        );
    }
}

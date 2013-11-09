<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\NewMessageManager;

/**
 * Test file for Miliooo\Messaging\Manager\NewMessageManager
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewMessageManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewMessageManager
     */
    private $manager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $msgRepo;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadRepo;

    public function setUp()
    {
        $this->msgRepo = $this->getMock('Miliooo\Messaging\Repository\MessageRepositoryInterface');
        $this->threadRepo = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->manager = new NewMessageManager($this->msgRepo, $this->threadRepo);
    }

    public function testSaveNewThread()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $message->expects($this->once())->method('getThread')->will($this->returnValue($thread));
        $this->msgRepo->expects($this->once())->method('save')
            ->with($message, false);
        $this->threadRepo->expects($this->once())->method('save')
            ->with($thread, true);

        $this->manager->saveNewThread($message);
    }

    public function testSaveNewReply()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');

        $message->expects($this->once())->method('getThread')->will($this->returnValue($thread));
        $this->msgRepo->expects($this->once())->method('save')
            ->with($message, false);
        $this->threadRepo->expects($this->once())->method('save')
            ->with($thread, true);

        $this->manager->saveNewReply($message, $thread);
    }
}

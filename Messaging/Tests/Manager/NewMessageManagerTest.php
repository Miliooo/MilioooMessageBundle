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
    private $entityManager;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->manager = new NewMessageManager($this->entityManager);
    }

    public function testSaveNewThread()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $message->expects($this->once())->method('getThread')->will($this->returnValue($thread));
        $this->entityManager->expects($this->at(0))->method('persist')->with($message);
        $this->entityManager->expects($this->at(1))->method('persist')->with($thread);
        $this->entityManager->expects($this->once())->method('flush')->with();

        $this->manager->saveNewThread($message);
    }

    public function testSaveNewReply()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');

        $this->manager->saveNewReply($message, $thread);
    }
}

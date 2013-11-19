<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Event;

use Miliooo\Messaging\Event\ReadStatusMessageEvent;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Test file for ReadStatusMessageEvent
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusMessageEventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var ReadStatusMessageEvent
     */
    private $event;

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
     * @var ParticipantInterface
     */
    private $participant;

    public function setUp()
    {
        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->participant = new ParticipantTestHelper(1);
        $this->event = new ReadStatusMessageEvent($this->message, $this->participant);
    }

    public function testGetMessage()
    {
        $this->assertEquals($this->message, $this->event->getMessage());
    }

    public function testGetThread()
    {
        $this->message->expects($this->once())->method('getThread')->will($this->returnValue($this->thread));
        $this->assertEquals($this->thread, $this->event->getThread());
    }

    public function testGetPreviousReadStatus()
    {
        $this->message->expects($this->once())->method('getMessageMetaForParticipant')->with($this->participant)
            ->will($this->returnValue($this->messageMeta));

        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_NEVER_READ));

        $this->assertSame(MessageMetaInterface::READ_STATUS_NEVER_READ, $this->event->getPreviousReadStatus());
    }

    public function testGetReadStatus()
    {
        $this->message->expects($this->once())->method('getMessageMetaForParticipant')->with($this->participant)
            ->will($this->returnValue($this->messageMeta));

        $this->messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));

        $this->assertSame(MessageMetaInterface::READ_STATUS_READ, $this->event->getReadStatus());
    }

    public function testGetParticipant()
    {
        $this->assertEquals($this->participant, $this->event->getParticipant());
    }

    public function testIsFirstTimeReadReturnsFalse()
    {
        $this->message->expects($this->any())->method('getMessageMetaForParticipant')->with($this->participant)
            ->will($this->returnValue($this->messageMeta));

        $this->messageMeta->expects($this->never())->method('getReadStatus');

        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_MARKED_UNREAD));

        $this->assertFalse($this->event->isFirstTimeRead());
    }

    public function testIsFirstTimeReadReturnsTrue()
    {
        $this->message->expects($this->any())->method('getMessageMetaForParticipant')->with($this->participant)
            ->will($this->returnValue($this->messageMeta));

        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_NEVER_READ));

        $this->messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));

        $this->assertTrue($this->event->isFirstTimeRead());
    }
}

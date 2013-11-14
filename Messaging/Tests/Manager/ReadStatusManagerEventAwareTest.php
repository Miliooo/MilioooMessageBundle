<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\ReadStatusManagerEventAware;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;
use Miliooo\Messaging\Event\MilioooMessagingEvents;
use Miliooo\Messaging\Event\ReadStatusMessageEvent;

/**
 * Test file for Miliooo\Messaging\Manager\NewMessageManager
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusManagerEventAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test.
     *
     * @var ReadStatusManagerEventAware
     */
    private $object;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $readStatusManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $eventDispatcher;

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
     * @var ReadStatus
     */
    private $newReadStatus;

    public function setUp()
    {
        $this->readStatusManager = $this->getMock('Miliooo\Messaging\Manager\ReadStatusManagerInterface');
        $this->eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
        $this->object = new ReadStatusManagerEventAware($this->readStatusManager, $this->eventDispatcher);
        $this->participant = new ParticipantTestHelper(1);
        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->newReadStatus = new ReadStatus(MessageMetaInterface::READ_STATUS_READ);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\ReadStatusManagerInterface', $this->object);
    }

    public function testUpdateReadStatusFiresEventWhenReadStatusChanged()
    {
        //expects call to the readstatusmanager to get the updates
        $this->readStatusManager->expects($this->once())
            ->method('updateReadStatusForMessageCollection')
            ->with($this->newReadStatus, $this->participant, [$this->message])
            ->will($this->returnValue([$this->message]));

        //expects call to the message to get the messagemeta for the participant
        $this->message->expects($this->once())->method('getMessageMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($this->messageMeta));

        //expects call on the read status
        $this->messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));

        //expects call on the previous read status
        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_NEVER_READ));

        // create the event
        $event = new ReadStatusMessageEvent(
            $this->message,
            $this->participant,
            MessageMetaInterface::READ_STATUS_NEVER_READ,
            MessageMetaInterface::READ_STATUS_READ
        );

        $this->eventDispatcher->expects($this->once())->method('dispatch')
            ->with(MilioooMessagingEvents::READ_STATUS_CHANGED, $event);

        $result = $this->object->updateReadStatusForMessageCollection(
            $this->newReadStatus,
            $this->participant,
            [$this->message]
        );

        $this->assertEquals([$this->message], $result);
    }

    public function testUpdateReadStatusDoesNotDispatchWhenNoUpdates()
    {
        //expects call to the readstatusmanager to get the updates
        $this->readStatusManager->expects($this->once())
            ->method('updateReadStatusForMessageCollection')
            ->with($this->newReadStatus, $this->participant, [$this->message])
            ->will($this->returnValue([]));

        $this->eventDispatcher->expects($this->never())->method('dispatch');

        $result = $this->object->updateReadStatusForMessageCollection(
            $this->newReadStatus,
            $this->participant,
            [$this->message]
        );

        $this->assertEquals([], $result);
        $this->assertCount(0, $result);
    }
}

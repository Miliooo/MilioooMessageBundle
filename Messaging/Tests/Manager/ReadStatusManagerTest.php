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

    public function setUp()
    {
        $this->participant = new ParticipantTestHelper(1);
        $this->messageRepository = $this->getMock('Miliooo\Messaging\Repository\MessageRepositoryInterface');
        $this->readStatusManager = new ReadStatusManager($this->messageRepository);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\ReadStatusManagerInterface', $this->readStatusManager);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage expects array with messages as second argument
     */
    public function testMarkMessageCollectionAsReadExpectsArrayAsSecondArgument()
    {
        $message = $this->getMessageWithUnreadMessageMetaForParticipant();
        $this->readStatusManager->markMessageCollectionAsRead($this->participant, $message);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No message meta found for the given participant
     */
    public function testMarkMessageThrowsExceptionWhenNoMetaForParticipantFound()
    {
        $notParticipant = new ParticipantTestHelper('now participant');
        $message = $this->getMessageWithUnreadMessageMetaForParticipant();

        $this->readStatusManager->markMessageCollectionAsRead($notParticipant, [$message]);
    }

    public function testMarkMsgCollAsReadCallsSaveOnMessageRepository()
    {
        $message = $this->getMessageWithUnreadMessageMetaForParticipant();

        $this->messageRepository->expects($this->once())
            ->method('save')
            ->with($message, false);

        $this->messageRepository->expects($this->once())
            ->method('flush');

        $this->readStatusManager->markMessageCollectionAsRead($this->participant, [$message]);
    }

    public function testMarkMsgCollAsReadCallsFlushOnlyOnce()
    {
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

        $this->readStatusManager->markMessageCollectionAsRead($this->participant, [$message, $message2]);
    }

    public function testMarkMsgCollAsReadCallsRightMethodsOnUnreadMessage()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');

        $message->expects($this->once())->method('getMessageMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($messageMeta));

        $messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_NEVER_READ));

        $messageMeta->expects($this->once())->method('setReadStatus')->with(new ReadStatus(MessageMetaInterface::READ_STATUS_READ));
        $messageMeta->expects($this->once())->method('setNewRead')->with(true);

        $this->readStatusManager->markMessageCollectionAsRead($this->participant, [$message]);
    }

    public function testMarkMsgCollAsReadCallsRightMethodsOnReadMessage()
    {
        $message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');

        $message->expects($this->once())->method('getMessageMetaForParticipant')
            ->with($this->participant)
            ->will($this->returnValue($messageMeta));

        $messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));

        $messageMeta->expects($this->never())->method('setReadStatus');
        $messageMeta->expects($this->never())->method('setNewRead');

        $this->readStatusManager->markMessageCollectionAsRead($this->participant, [$message]);
    }

    public function testMarkMsgCollAsReadDoesNotUpdateAlreadyReadMessages()
    {
        $message = $this->getMessageWithUnreadMessageMetaForParticipant();
        //set the status to read
        $message->getMessageMetaForParticipant($this->participant)->setReadStatus(new ReadStatus(MessageMetaInterface::READ_STATUS_READ));

        $this->messageRepository->expects($this->never())
            ->method('save');
        $this->messageRepository->expects($this->never())
            ->method('flush');

        $this->readStatusManager->markMessageCollectionAsRead($this->participant, [$message]);
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

        $messageMeta->setParticipant($this->participant);
        $messageMeta->setReadStatus(new ReadStatus(MessageMetaInterface::READ_STATUS_NEVER_READ));
        $messageMeta->setMessage($message);
        $message->addMessageMeta($messageMeta);

        return $message;
    }
}

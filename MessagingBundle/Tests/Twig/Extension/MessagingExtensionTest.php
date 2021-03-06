<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Twig\Extension;

use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\MessagingBundle\Twig\Extension\MessagingExtension;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Test file for Miliooo\MessagingBundle\Twig\Extension\MessagingExtension
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessagingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var MessagingExtension
     */
    private $messagingExtension;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $participantProvider;

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

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $unreadMessagesProvider;

    public function setUp()
    {
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->unreadMessagesProvider = $this->getMock('Miliooo\Messaging\Notifications\UnreadMessagesProviderInterface');
        $this->messagingExtension = new MessagingExtension($this->participantProvider, $this->unreadMessagesProvider);

        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadMeta = $this->getMock('Miliooo\Messaging\Model\ThreadMetaInterface');
        $this->loggedInUser = new ParticipantTestHelper(1);
    }

    public function testGetName()
    {
        $this->assertEquals('miliooo_messaging', $this->messagingExtension->getName());
    }

    public function testIsMessageReadWithNoMessageMetaForParticipant()
    {
        $this->expectsLoggedInUser();
        $this->expectsNoMessageMetaForLoggedInUser();

        $this->assertFalse($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testIsMessageReadWithPreviousStateNull()
    {
        $this->expectsLoggedInUser();
        $this->expectsMessageMetaForLoggedInUser();
        //read status has not changed
        $this->messageMeta->expects($this->once())
            ->method('getPreviousReadStatus')
            ->will($this->returnValue(null));
        //so it cant be a new read
        $this->messageMeta->expects($this->never())
            ->method('getReadStatus');

        $this->assertFalse($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testIsMessageReadWithPreviousReadStatusButReadStatusNotRead()
    {
        $this->expectsLoggedInUser();
        $this->expectsMessageMetaForLoggedInUser();
        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));

        $this->messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_MARKED_UNREAD));

        $this->assertFalse($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testIsNewReadWReturnsTrue()
    {
        $this->expectsLoggedInUser();
        $this->expectsMessageMetaForLoggedInUser();

        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_NEVER_READ));

        $this->messageMeta->expects($this->once())->method('getReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));

        $this->assertTrue($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testGetThreadUnreadCount()
    {
        $this->expectsLoggedInUser();
        $this->expectsThreadMetaForLoggedInUser();

        $this->threadMeta->expects($this->once())->method('getUnreadMessageCount')
            ->will($this->returnValue(5));

        $this->assertEquals(5, $this->messagingExtension->getThreadUnreadCount($this->thread));
    }

    public function testGetUnreadMessageCountWhenParticipantNotThreadParticipant()
    {
        $this->expectsLoggedInUser();
        $this->expectsNoThreadMetaForLoggedInUser();
        $this->assertEquals(0, $this->messagingExtension->getThreadUnreadCount($this->thread));
    }

    public function testGetFunctions()
    {
        $this->assertArrayHasKey('miliooo_messaging_is_new_read', $this->messagingExtension->getFunctions());
        $this->assertArrayHasKey('miliooo_messaging_thread_unread_count', $this->messagingExtension->getFunctions());
        $this->assertArrayHasKey('miliooo_messaging_unread_messages_count', $this->messagingExtension->getFunctions());
    }

    public function testGetUnreadMessagesCount()
    {
        $this->expectsLoggedInUser();
        $this->unreadMessagesProvider->expects($this->once())
            ->method('getUnreadMessageCountForParticipant')
            ->with($this->loggedInUser)
            ->will($this->returnValue(3));

        $this->assertEquals(3, $this->messagingExtension->getUnreadMessagesCount());
    }

    protected function expectsLoggedInUser()
    {
        $this->participantProvider
            ->expects($this->once())
            ->method('getAuthenticatedParticipant')
            ->will($this->returnValue($this->loggedInUser));
    }

    protected function expectsMessageMetaForLoggedInUser()
    {
        $this->message->expects($this->once())
            ->method('getMessageMetaForParticipant')
            ->with($this->loggedInUser)
            ->will($this->returnValue($this->messageMeta));
    }

    protected function expectsThreadMetaForLoggedInUser()
    {
        $this->thread->expects($this->once())->method('getThreadMetaForParticipant')
            ->with($this->loggedInUser)
            ->will($this->returnValue($this->threadMeta));
    }

    protected function expectsNoThreadMetaForLoggedInUser()
    {
        $this->thread->expects($this->once())->method('getThreadMetaForParticipant')
            ->with($this->loggedInUser)
            ->will($this->returnValue(null));
    }

    protected function expectsNoMessageMetaForLoggedInUser()
    {
        $this->message->expects($this->once())
            ->method('getMessageMetaForParticipant')
            ->with($this->loggedInUser)
            ->will($this->returnValue(null));
    }
}

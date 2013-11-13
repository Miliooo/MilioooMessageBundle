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
 * Test file for MessagingExtension
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
     * @var ParticipantInterface
     */
    private $loggedInUser;

    public function setUp()
    {
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->messagingExtension = new MessagingExtension($this->participantProvider);

        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->messageMeta = $this->getMock('Miliooo\Messaging\Model\MessageMetaInterface');
        $this->loggedInUser = new ParticipantTestHelper(1);
    }

    public function testGetName()
    {
        $this->assertEquals('miliooo_messaging', $this->messagingExtension->getName());
    }

    public function testIsMessageReadWithPreviousStateMarkedUnread()
    {
        $this->expectsLoggedInUser();
        $this->expectsMessageMetaForLoggedInUser();
        $this->messageMeta->expects($this->once())
            ->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_MARKED_UNREAD));
        $this->assertTrue($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testIsMessageReadWithNewPreviousStateRead()
    {
        $this->expectsLoggedInUser();
        $this->expectsMessageMetaForLoggedInUser();
        $this->messageMeta->expects($this->once())->method('getPreviousReadStatus')
            ->will($this->returnValue(MessageMetaInterface::READ_STATUS_READ));
        $this->assertFalse($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testIsNewReadWithGetMessageMetaForParticipantReturnsNull()
    {
        $this->expectsLoggedInUser();
        $this->message->expects($this->once())
            ->method('getMessageMetaForParticipant')
            ->with($this->loggedInUser)
            ->will($this->returnValue(null));

        $this->assertFalse($this->messagingExtension->isMessageNewRead($this->message));
    }

    public function testGetFunctions()
    {
        $this->assertArrayHasKey('miliooo_messaging_is_new_read', $this->messagingExtension->getFunctions());
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
}

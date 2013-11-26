<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Tests\Notifications;

use Miliooo\Messaging\Notifications\UnreadMessagesProviderDoctrine;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Test file for Miliooo\Messaging\Notifications\UnreadMessagesProviderDoctrine
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class UnreadMessagesProviderDoctrineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadRepository;

    /**
     * The class under test.
     *
     * @var UnreadMessagesProviderDoctrine
     */
    private $unreadMessageProvider;

    /**
     * @var ParticipantInterface
     */
    private $participant;

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->unreadMessageProvider = new UnreadMessagesProviderDoctrine($this->threadRepository);
        $this->participant = new ParticipantTestHelper('1');
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Miliooo\Messaging\Notifications\UnreadMessagesProviderInterface',
            $this->unreadMessageProvider
        );
    }

    public function testGetUnreadCount()
    {
        $this->threadRepository->expects($this->once())->method('getUnreadMessageCountForParticipant')
            ->with($this->participant)
            ->will($this->returnValue(1));
        $this->assertEquals(1, $this->unreadMessageProvider->getUnreadMessageCountForParticipant($this->participant));
    }
}

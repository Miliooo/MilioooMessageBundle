<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider\Folder;

use Miliooo\Messaging\ThreadProvider\Folder\InboxProvider;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for the inboxprovider
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var InboxProvider
     */
    private $inboxProvider;
    private $threadRepository;

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->inboxProvider = new InboxProvider($this->threadRepository);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\ThreadProvider\Folder\InboxProviderInterface', $this->inboxProvider);
    }

    public function testGetInboxThreadsRepositoryReturnsNull()
    {
        $participant = new ParticipantTestHelper(1);

        $this->threadRepository->expects($this->once())
            ->method('getInboxThreadsForParticipant')
            ->with($participant)
            ->will($this->returnValue(null));

        $this->assertNull($this->inboxProvider->getInboxThreads($participant));
    }

    public function testGetInboxThreadsRepositoryReturnsThreads()
    {
        $participant = new ParticipantTestHelper(1);
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $arrayOfThreads = [$thread];

        $this->threadRepository->expects($this->once())
            ->method('getInboxThreadsForParticipant')
            ->with($participant)
            ->will($this->returnValue($arrayOfThreads));

        $this->assertEquals($arrayOfThreads, $this->inboxProvider->getInboxThreads($participant));
    }
}

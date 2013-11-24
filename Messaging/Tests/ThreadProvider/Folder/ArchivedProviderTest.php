<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider\Folder;

use Miliooo\Messaging\ThreadProvider\Folder\ArchivedProvider;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for the archived provider
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ArchivedProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ArchivedProvider
     */
    private $provider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadRepository;

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->provider = new ArchivedProvider($this->threadRepository);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\ThreadProvider\Folder\ArchivedProviderInterface', $this->provider);
    }

    public function testGetInboxThreadsRepositoryReturnsNull()
    {
        $participant = new ParticipantTestHelper(1);

        $this->threadRepository->expects($this->once())
            ->method('getArchivedThreadsForParticipant')
            ->with($participant)
            ->will($this->returnValue(null));

        $this->assertNull($this->provider->getArchivedThreads($participant));
    }

    public function testGetInboxThreadsRepositoryReturnsThreads()
    {
        $participant = new ParticipantTestHelper(1);
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $arrayOfThreads = [$thread];

        $this->threadRepository->expects($this->once())
            ->method('getArchivedThreadsForParticipant')
            ->with($participant)
            ->will($this->returnValue($arrayOfThreads));

        $this->assertEquals($arrayOfThreads, $this->provider->getArchivedThreads($participant));
    }
}

<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider\Folder;

use Miliooo\Messaging\ThreadProvider\Folder\OutboxProvider;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Class OutboxProviderTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var OutboxProvider
     */
    private $outboxProvider;
    private $participant;
    /**
     * @var
     */
    private $threadRepository;

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->participant = new ParticipantTestHelper(1);
        $this->outboxProvider = new OutboxProvider($this->threadRepository);
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Miliooo\Messaging\ThreadProvider\Folder\OutboxProviderInterface',
            $this->outboxProvider
        );
    }

    public function testGetOutBoxThreadsReturnsNullWhenNoResults()
    {
        $this->threadRepository->expects($this->once())->method('getOutboxThreadsForParticipant')
            ->with($this->participant)->will($this->returnValue(null));

        $result = $this->outboxProvider->getOutboxThreads($this->participant);
        $this->assertNull($result);
    }

    public function testGetOutBoThreadsReturnsThreadsWhenResults()
    {
        $participant = new ParticipantTestHelper(1);
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $arrayOfThreads = [$thread];

        $this->threadRepository->expects($this->once())
            ->method('getOutboxThreadsForParticipant')
            ->with($participant)
            ->will($this->returnValue($arrayOfThreads));

        $this->assertEquals($arrayOfThreads, $this->outboxProvider->getOutboxThreads($participant));
    }
}

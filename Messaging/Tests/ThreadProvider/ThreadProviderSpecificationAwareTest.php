<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider;

use Miliooo\Messaging\ThreadProvider\ThreadProviderSpecificationAware;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for ThreadProviderSpecificationAware
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadProviderSpecificationAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var ThreadProviderSpecificationAware
     */
    private $secureProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $canSeeThread;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    public function setUp()
    {
        $this->canSeeThread = $this->getMockBuilder('Miliooo\Messaging\Specifications\CanSeeThreadSpecification')
            ->disableOriginalConstructor()->getMock();
        $this->threadProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\ThreadProviderInterface');
        $this->secureProvider = new ThreadProviderSpecificationAware($this->threadProvider, $this->canSeeThread);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
    }

    public function testFindThreadReturnsNullWhenNoSuchThread()
    {
        $participant = new ParticipantTestHelper(1);

        $this->threadProvider->expects($this->once())
            ->method('findThreadById')->with(1)
            ->will($this->returnValue(null));

        $this->canSeeThread->expects($this->never())
            ->method('isSatisfiedBy');

        $this->assertNull($this->secureProvider->findThreadForParticipant($participant, 1));
    }

    public function testFindThreadSpecReturnsTrue()
    {
        $participant = new ParticipantTestHelper(1);

        $this->expectsThreadFound();
        $this->canSeeThread->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($participant, $this->thread)
            ->will($this->returnValue(true));

        $this->assertSame($this->thread, $this->secureProvider->findThreadForParticipant($participant, 1));
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage Not allowed to see this thread
     */
    public function testFindThreadSpecReturnsFalseThrowsError()
    {
        $participant = new ParticipantTestHelper(2);

        $this->expectsThreadFound();
        $this->canSeeThread->expects($this->once())
            ->method('isSatisfiedBy')
            ->with($participant, $this->thread)
            ->will($this->returnValue(false));

        $this->secureProvider->findThreadForParticipant($participant, 1);
    }

    protected function expectsThreadFound()
    {
        $this->threadProvider->expects($this->once())
            ->method('findThreadById')->with(1)
            ->will($this->returnValue($this->thread));
    }
}
<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider\Folder;

use Miliooo\Messaging\ThreadProvider\Folder\OutboxProviderPagerFanta;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Test file for outbox provider pagerfanta
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxProviderPagerFantaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OutboxProviderPagerFanta
     */
    private $outboxProviderPf;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadRepository;

    /**
     * @var ParticipantInterface
     */
    private $participant;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $queryBuilderMock;

    public function setUp()
    {
        $this->participant = new ParticipantTestHelper(1);
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->outboxProviderPf = new OutboxProviderPagerFanta($this->threadRepository, 15);
        $this->queryBuilderMock =  $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetOutboxThreadsReturnsPagerFantaObject()
    {
        $this->threadRepository
            ->expects($this->once())
            ->method('getOutboxThreadsForParticipantQueryBuilder')
            ->will($this->returnValue($this->queryBuilderMock));

        $result = $this->outboxProviderPf->getOutboxThreadsPagerfanta($this->participant, 1);
        $this->assertInstanceOf('Pagerfanta\Pagerfanta', $result);
    }

    public function testPagerFantaGetsRightMethodCalls()
    {
        //mock the pagerfanta method
        $this->outboxProviderPf =
            $this->getMockBuilder('Miliooo\Messaging\ThreadProvider\Folder\OutboxProviderPagerFanta')
            ->enableOriginalConstructor()
            ->setConstructorArgs([$this->threadRepository, 15])
            ->setMethods(['getPagerFanta'])
            ->getMock();

        //thread repository expects querybuilder as return value
        $this->threadRepository
            ->expects($this->once())
            ->method('getOutboxThreadsForParticipantQueryBuilder')
            ->will($this->returnValue($this->queryBuilderMock));

        //mock pagerfanta instance
        $pagerfantaMock = $this->getMockBuilder('Pagerfanta\Pagerfanta')
            ->disableOriginalConstructor()
            ->getMock();

        //return pagerfanta instance
        $this->outboxProviderPf->expects($this->once())
            ->method('getPagerFanta')
            ->will($this->returnValue($pagerfantaMock));

        //expectations on pagerfanta
        $pagerfantaMock->expects($this->once())
            ->method('setMaxPerPage')
            ->with(15);
        $pagerfantaMock->expects($this->once())
            ->method('setCurrentPage')
            ->with(2);

        $this->outboxProviderPf->getOutboxThreadsPagerfanta($this->participant, 2);
    }
}

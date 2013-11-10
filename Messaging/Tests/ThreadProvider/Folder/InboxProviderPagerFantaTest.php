<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider\Folder;

use Miliooo\Messaging\ThreadProvider\Folder\InboxProviderPagerFanta;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Test file for inbox provider pagerfanta
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxProviderPagerFantaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InboxProviderPagerFanta
     */
    private $inboxProviderPf;

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
        $this->inboxProviderPf = new InboxProviderPagerFanta($this->threadRepository, 15);
        $this->queryBuilderMock =  $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Miliooo\Messaging\ThreadProvider\Folder\InboxProviderPagerFanta',
            $this->inboxProviderPf
        );
    }

    public function testGetOutboxThreadsReturnsPagerFantaObject()
    {
        $this->threadRepository
            ->expects($this->once())
            ->method('getInboxThreadsForParticipantQueryBuilder')
            ->will($this->returnValue($this->queryBuilderMock));

        $result = $this->inboxProviderPf->getInboxThreadsPagerfanta($this->participant, 1);
        $this->assertInstanceOf('Pagerfanta\Pagerfanta', $result);
    }
}

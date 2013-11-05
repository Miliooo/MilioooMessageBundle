<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\ThreadProvider;

use Miliooo\Messaging\ThreadProvider\ThreadProvider;

/**
 * Test file for Miliooo\Messaging\ThreadProvider\ThreadProvider
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ThreadProvider
     */
    protected $provider;
    protected $em;
    protected $threadClass;
    protected $entityRepository;

    public function setUp()
    {
        $this->em = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->threadClass = 'Miliooo\Messaging\TestHelpers\Model\Thread';
        $this->provider = new ThreadProvider($this->em, $this->threadClass);
        $this->entityRepository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')->disableOriginalConstructor()->getMock();
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\ThreadProvider\ThreadProviderInterface', $this->provider);
    }

    public function testFindThreadByIdWithExistingThreadReturnsThread()
    {
        $this->expectsRepository();
        $threadMock = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->entityRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->will($this->returnValue($threadMock));

        $this->assertSame($threadMock, $this->provider->findThreadById(1));
    }

    public function testFindThreadByIdWithNonExistingThreadReturnsNull()
    {
        $this->expectsRepository();

        $this->entityRepository
            ->expects($this->once())
            ->method('find')
            ->with(1)
            ->will($this->returnValue(null));

        $this->assertNull($this->provider->findThreadById(1));
    }

    protected function expectsRepository()
    {
        $this->em
            ->expects($this->once())
            ->method('getRepository')
            ->with($this->threadClass)
            ->will($this->returnValue($this->entityRepository));
    }
}

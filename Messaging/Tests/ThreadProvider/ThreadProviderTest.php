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
    protected $threadRepository;

    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->provider = new ThreadProvider($this->threadRepository);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\ThreadProvider\ThreadProviderInterface', $this->provider);
    }

    public function testFindThreadByIdWithExistingThreadReturnsThread()
    {
        $threadMock = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadRepository
            ->expects($this->once())
            ->method('findThread')
            ->with(1)
            ->will($this->returnValue($threadMock));

        $this->assertSame($threadMock, $this->provider->findThreadById(1));
    }

    public function testFindThreadByIdWithNonExistingThreadReturnsNull()
    {
        $this->threadRepository
            ->expects($this->once())
            ->method('findThread')
            ->with(1)
            ->will($this->returnValue(null));

        $this->assertNull($this->provider->findThreadById(1));
    }
}

<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\DeleteThreadManager;

/**
 * Class DeleteThreadManagerTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class DeleteThreadManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test.
     *
     * @var DeleteThreadManager
     */
    private $deleteThreadManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadRepository;

    /**
    * @var \PHPUnit_Framework_MockObject_MockObject
    */
    private $thread;


    public function setUp()
    {
        $this->threadRepository = $this->getMock('Miliooo\Messaging\Repository\ThreadRepositoryInterface');
        $this->deleteThreadManager = new DeleteThreadManager($this->threadRepository);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\DeleteThreadManagerInterface', $this->deleteThreadManager);
    }

    public function testDeleteThread()
    {
        $this->threadRepository->expects($this->once())->method('delete')
            ->with($this->thread, true);

        $this->deleteThreadManager->deleteThread($this->thread, true);
    }
}

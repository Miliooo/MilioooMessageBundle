<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\DeleteThreadManagerSpecificationAware;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Test file for deleteThreadManagerSpecificationAware
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class DeleteThreadManagerSpecificationAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class under test.
     *
     * @var DeleteThreadManagerSpecificationAware
     */
    private $deleteThreadManagerSpecAware;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $deleteThreadManager;

    /**
     * @var ParticipantInterface
     */
    private $participant;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $canDeleteThread;

    public function setUp()
    {
        $this->deleteThreadManager = $this->getMock('Miliooo\Messaging\Manager\DeleteThreadManagerInterface');
        $this->canDeleteThread = $this->getMockBuilder('Miliooo\Messaging\Specifications\CanDeleteThreadSpecification')
            ->disableOriginalConstructor()->getMock();

        $this->deleteThreadManagerSpecAware = new DeleteThreadManagerSpecificationAware(
            $this->deleteThreadManager,
            $this->canDeleteThread
        );

        $this->participant = new ParticipantTestHelper(1);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Miliooo\Messaging\Manager\DeleteThreadManagerSecureInterface',
            $this->deleteThreadManagerSpecAware
        );
    }

    public function testDeleteThreadSpecificationReturnsTrue()
    {
        $this->canDeleteThread->expects($this->once())->method('isSatisfiedBy')
            ->with($this->participant, $this->thread)
            ->will($this->returnValue(true));

        $this->deleteThreadManager->expects($this->once())
            ->method('deleteThread')->with($this->thread);

        $this->deleteThreadManagerSpecAware->deleteThread($this->participant, $this->thread);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage not authorised to delete this thread
     */
    public function testDeleteThreadSpecificationReturnsFalse()
    {
        $this->canDeleteThread->expects($this->once())->method('isSatisfiedBy')
            ->with($this->participant, $this->thread)
            ->will($this->returnValue(false));

        $this->deleteThreadManager->expects($this->never())
            ->method('deleteThread');

        $this->deleteThreadManagerSpecAware->deleteThread($this->participant, $this->thread);
    }
}

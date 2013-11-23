<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Manager;

use Miliooo\Messaging\Manager\CanMessageRecipientManager;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Class CanMessageRecipientManagerTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test.
     *
     * @var CanMessageRecipientManager
     */
    private $manager;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $specification;

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;

    /**
     * @var ParticipantInterface
     */
    private $recipient;

    public function setUp()
    {
        $this->loggedInUser = new ParticipantTestHelper('1');
        $this->recipient = new ParticipantTestHelper('2');
        $this->specification = $this->getMock('Miliooo\Messaging\Specifications\CanMessageRecipientSpecification');
        $this->manager = new CanMessageRecipientManager($this->specification);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\Manager\CanMessageRecipientManagerInterface', $this->manager);
    }

    public function testCanMessageRecipientSpecReturnsFalse()
    {
        $this->specification->expects($this->once())->method('isSatisfiedBy')
            ->with($this->loggedInUser, $this->recipient)
            ->will($this->returnValue(false));

        $this->assertFalse($this->manager->canMessageRecipient($this->loggedInUser, $this->recipient));
    }

    public function testCanMessageRecipientSpecReturnsTrue()
    {
        $this->specification->expects($this->once())->method('isSatisfiedBy')
            ->with($this->loggedInUser, $this->recipient)
            ->will($this->returnValue(true));

        $this->assertTrue($this->manager->canMessageRecipient($this->loggedInUser, $this->recipient));
    }
}

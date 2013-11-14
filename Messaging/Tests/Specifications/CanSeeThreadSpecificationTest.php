<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Specifications;

use Miliooo\Messaging\Specifications\CanSeeThreadSpecification;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Test file for Miliooo\Messaging\Specifications\CanSeeThreadSpecification
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanSeeThreadSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class under test.
     *
     * @var CanSeeThreadSpecification
     */
    private $specification;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    /**
     * @var ParticipantInterface
     */
    private $participant;

    public function setUp()
    {
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->participant = new ParticipantTestHelper(1);
        $this->specification = new CanSeeThreadSpecification;
    }

    public function testIsSatisfiedByReturnsTrueWhenParticipant()
    {
        $this->thread->expects($this->once())->method('isParticipant')
            ->with($this->participant)->will($this->returnValue(true));
        $this->assertTrue($this->specification->isSatisfiedBy($this->participant, $this->thread));
    }

    public function testIsSatisfiedByReturnsFalseWhenNotParticipant()
    {
        $this->thread->expects($this->once())->method('isParticipant')
            ->with($this->participant)->will($this->returnValue(false));
        $this->assertFalse($this->specification->isSatisfiedBy($this->participant, $this->thread));
    }
}

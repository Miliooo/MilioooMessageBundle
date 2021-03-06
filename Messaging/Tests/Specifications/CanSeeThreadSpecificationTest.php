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
     * @var CanSeeThreadSpecification|\PHPUnit_Framework_MockObject_MockObject
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

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $isParticipantThreadSpecification;

    public function setUp()
    {
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->participant = new ParticipantTestHelper(1);

        $this->specification = $this->getMockBuilder('Miliooo\Messaging\Specifications\CanSeeThreadSpecification')
            ->setMethods(['getIsParticipantThreadSpecification'])
            ->getMock();
        $this->isParticipantThreadSpecification = $this->getMock('Miliooo\Messaging\Specifications\IsParticipantThreadSpecification');
        $this->specification->expects($this->once())->method('getIsParticipantThreadSpecification')
            ->will($this->returnValue($this->isParticipantThreadSpecification));
    }

    public function testIsSatisfiedByReturnsTrueWhenParticipant()
    {
        $this->isParticipantThreadSpecification->expects($this->once())->method('isSatisfiedBy')
            ->with($this->participant, $this->thread)
            ->will($this->returnValue(true));

        $this->assertTrue($this->specification->isSatisfiedBy($this->participant, $this->thread));
    }

    public function testIsSatisfiedByReturnsFalseWhenNotParticipant()
    {
        $this->isParticipantThreadSpecification->expects($this->once())->method('isSatisfiedBy')
            ->with($this->participant, $this->thread)
            ->will($this->returnValue(false));

        $this->assertFalse($this->specification->isSatisfiedBy($this->participant, $this->thread));
    }
}

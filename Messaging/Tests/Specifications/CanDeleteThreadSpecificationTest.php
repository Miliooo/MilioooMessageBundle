<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Specifications;

use Miliooo\Messaging\Specifications\CanDeleteThreadSpecification;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Class CanDeleteThreadSpecification
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanDeleteThreadSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class under test.
     *
     * @var CanDeleteThreadSpecification
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
        $this->specification = new CanDeleteThreadSpecification();
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->participant = new ParticipantTestHelper(1);
    }

    public function testIsSatisfiedBy()
    {
        $this->assertFalse($this->specification->isSatisfiedBy($this->participant, $this->thread));
    }
}

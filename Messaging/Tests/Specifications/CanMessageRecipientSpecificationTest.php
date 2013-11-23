<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Specifications;

use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Specifications\CanMessageRecipientSpecification;

/**
 * Test file for CanMessageRecipientSpecification
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientSpecificationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test.
     *
     * @var CanMessageRecipientSpecification
     */
    private $specification;

    public function setUp()
    {
        $this->specification = new CanMessageRecipientSpecification();
    }

    public function testIsSatisfiedBy()
    {
        $currentParticipant = new ParticipantTestHelper('1');
        $recipient = new ParticipantTestHelper('2');

        $this->assertTrue($this->specification->isSatisfiedBy($currentParticipant, $recipient));
    }
}

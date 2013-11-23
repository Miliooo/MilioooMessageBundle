<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Validator\Constraint;

use Miliooo\MessagingBundle\Validator\Constraint\CanMessageRecipient;

/**
 * Test file for can message recipient.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class CanMessageRecipientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Class under test.
     *
     * @var CanMessageRecipient
     */
    private $canMessageRecipient;

    public function setUp()
    {
        $this->canMessageRecipient = new CanMessageRecipient();
    }

    public function testValidatedBy()
    {
        $this->assertSame('miliooo_messaging.validator.can_message_recipient', $this->canMessageRecipient->validatedBy());
    }

    public function testTargets()
    {
        $this->assertSame(canMessageRecipient::PROPERTY_CONSTRAINT, $this->canMessageRecipient->getTargets());
    }

    public function testMessage()
    {
        $this->assertAttributeEquals('validate.can_not_message_recipient', 'message', $this->canMessageRecipient);
    }
}
